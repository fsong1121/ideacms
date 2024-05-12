<?php
// +----------------------------------------------------------------------
// | 专注于产品，坚持小而美，赋能开发者，助力企业发展！
// +----------------------------------------------------------------------
// | Copyright (c) 2009~2049 https://www.ideacms.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | 程序开源并非等于免费,商业使用务必购买正版授权,以免引起不必要的法律纠纷.
// +----------------------------------------------------------------------
namespace app\common\logic\admin;

use think\facade\Cache;
use think\facade\Db;

class LocalApp extends Base
{
    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = get_addons_list();
            if($param['keys'] != '') {
                foreach ($list as $key => $value) {
                    if(str_contains($value['name'],$param['keys']) === false && str_contains($value['title'],$param['keys']) === false && str_contains($value['description'],$param['keys']) === false) {
                        unset($list[$key]);
                    }
                }
            }
            return [
                'code' => 0,
                'msg' => '',
                'count' => count($list),
                'data' => $list
            ];
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 安装
     * @param string $name
     * @return array
     */
    public function install(string $name) : array
    {
        // 启动事务
        Db::startTrans();
        try {
            $addon = get_addons_instance($name);
            if (!$addon) {
                return fail('不存在的插件');
            } else {
                $info = $addon->getInfo();
                if($info['isInstall'] != 1) {
                    //安装步骤 1:安装数据库 2:设置后台菜单 3:更新ini文件
                    //安装数据库
                    $sqlPath = root_path() . "addons" . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "install.sql";
                    if(file_exists($sqlPath)) {
                        $sql = file_get_contents($sqlPath);
                        $sql = str_replace("\r", "", $sql);
                        $sql = str_replace("\n", "", $sql);
                        $sql = str_replace("{PREFIX}", env('DB_PREFIX', ''), $sql);
                        $sqlList = explode(";", $sql);
                        foreach ($sqlList as $value) {
                            try {
                                if(!empty($value)) {
                                    Db::execute($value);
                                }
                            } catch (\Exception $e) {
                                // 回滚事务
                                Db::rollback();
                                return fail($e->getMessage());
                            }
                       }
                    }
                    //设置后台菜单
                    $menu = get_addons_menu($name);
                    if(!empty($menu)) {
                        $parentId = 57;
                        Db::name('admin_menu')->where('id',$parentId)->update(['is_show' => 1]);
                        foreach ($menu as $key => $value) {
                            $data = [
                                'parent_id' => $parentId,
                                'title' => $value['title'],
                                'controller' => $value['url'],
                                'other_operation' => $value['other_operation'],
                                'deep' => 2,
                                'type' => 0,
                                'is_turn' => 1,
                                'is_addon' => 1,
                                'is_show' => 1,
                            ];
                            $parentId = Db::name('admin_menu')->insertGetId($data);
                            if(!empty($value['childMenu'])) {
                                foreach ($value['childMenu'] as $k => $v) {
                                    $data = [
                                        'parent_id' => $parentId,
                                        'title' => $v['title'],
                                        'controller' => $v['url'],
                                        'other_operation' => $v['other_operation'],
                                        'deep' => 3,
                                        'sequence' => $k + 1,
                                        'type' => 0,
                                        'is_turn' => 1,
                                        'is_addon' => 1,
                                        'is_show' => 1,
                                    ];
                                    Db::name('admin_menu')->insert($data);
                                }
                            }
                        }
                    }
                    //更新ini文件
                    $info['isInstall'] = 1;
                    $info['status'] = 1;
                    $res = set_addons_info($name,$info);
                    if(!$res) {
                        // 回滚事务
                        Db::rollback();
                        return fail('更新ini文件失败');
                    } else {
                        // 提交事务
                        Db::commit();
                        Cache::clear();
                        return success();
                    }
                } else {
                    // 回滚事务
                    Db::rollback();
                    return fail('此插件已安装');
                }
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return fail($e->getMessage());
        }
    }

    /**
     * 启用
     * @param string $name
     * @return array
     */
    public function up(string $name) : array
    {
        // 启动事务
        Db::startTrans();
        try {
            $addon = get_addons_instance($name);
            if (!$addon) {
                return fail('不存在的插件');
            } else {
                $info = $addon->getInfo();
                if($info['isInstall'] == 1) {
                    if($info['status'] != 1) {
                        $parentId = 57;
                        $menu = get_addons_menu($name);
                        foreach ($menu as $value) {
                            Db::name('admin_menu')
                                ->where('title',$value['title'])
                                ->where('controller',$value['url'])
                                ->where('is_addon',1)
                                ->update(['is_show' => 1]);
                            foreach ($value['childMenu'] as $v) {
                                Db::name('admin_menu')
                                    ->where('title',$v['title'])
                                    ->where('controller',$v['url'])
                                    ->where('is_addon',1)
                                    ->update(['is_show' => 1]);
                            }
                        }
                        Db::name('admin_menu')->where('id',$parentId)->update(['is_show' => 1]);
                        //更新ini文件
                        $info['status'] = 1;
                        $res = set_addons_info($name,$info);
                        if(!$res) {
                            // 回滚事务
                            Db::rollback();
                            return fail('更新ini文件失败');
                        } else {
                            // 提交事务
                            Db::commit();
                            Cache::clear();
                            return success();
                        }
                    } else {
                        // 回滚事务
                        Db::rollback();
                        return fail('此插件已启用');
                    }
                } else {
                    // 回滚事务
                    Db::rollback();
                    return fail('此插件未安装，请先安装');
                }
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return fail($e->getMessage());
        }
    }

    /**
     * 停用
     * @param string $name
     * @return array
     */
    public function down(string $name) : array
    {
        // 启动事务
        Db::startTrans();
        try {
            $addon = get_addons_instance($name);
            if (!$addon) {
                return fail('不存在的插件');
            } else {
                $info = $addon->getInfo();
                if($info['isInstall'] == 1) {
                    if($info['status'] == 1) {
                        $parentId = 57;
                        $menu = get_addons_menu($name);
                        foreach ($menu as $value) {
                            Db::name('admin_menu')
                                ->where('title',$value['title'])
                                ->where('controller',$value['url'])
                                ->where('is_addon',1)
                                ->update(['is_show' => 0]);
                            foreach ($value['childMenu'] as $v) {
                                Db::name('admin_menu')
                                    ->where('title',$v['title'])
                                    ->where('controller',$v['url'])
                                    ->where('is_addon',1)
                                    ->update(['is_show' => 0]);
                            }
                        }
                        $childMenu = Db::name('admin_menu')->where('parent_id',$parentId)->where('is_show',1)->find();
                        if(empty($childMenu)) {
                            Db::name('admin_menu')->where('id', $parentId)->update(['is_show' => 0]);
                        }
                        //更新ini文件
                        $info['status'] = 0;
                        $res = set_addons_info($name,$info);
                        if(!$res) {
                            // 回滚事务
                            Db::rollback();
                            return fail('更新ini文件失败');
                        } else {
                            // 提交事务
                            Db::commit();
                            Cache::clear();
                            return success();
                        }
                    } else {
                        // 回滚事务
                        Db::rollback();
                        return fail('此插件已停用');
                    }
                } else {
                    // 回滚事务
                    Db::rollback();
                    return fail('此插件未安装，请先安装');
                }
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return fail($e->getMessage());
        }
    }

    /**
     * 卸载
     * @param string $name
     * @return array
     */
    public function uninstall(string $name) : array
    {
        // 启动事务
        Db::startTrans();
        try {
            $addon = get_addons_instance($name);
            if (!$addon) {
                return fail('不存在的插件');
            } else {
                $info = $addon->getInfo();
                if($info['isInstall'] != 0) {
                    //卸载步骤 1:删除表 2:删除后台菜单 3:更新ini文件
                    //删除表
                    $sqlPath = root_path() . "addons" . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "install.sql";
                    if(file_exists($sqlPath)) {
                        $sql = file_get_contents($sqlPath);
                        $sql = str_replace("\r", "", $sql);
                        $sql = str_replace("\n", "", $sql);
                        $sql = str_replace("{PREFIX}", env('DB_PREFIX', ''), $sql);
                        $sqlList = explode(";", $sql);
                        foreach ($sqlList as $value) {
                            try {
                                if(!empty($value) && str_contains($value, 'DROP')) {
                                    Db::execute($value);
                                }
                            } catch (\Exception $e) {
                                // 回滚事务
                                Db::rollback();
                                return fail($e->getMessage());
                            }
                        }
                    }
                    //删除后台菜单
                    $menu = get_addons_menu($name);
                    if(!empty($menu)) {
                        $parentId = 57;
                        foreach ($menu as $key => $value) {
                            Db::name('admin_menu')
                                ->where('title',$value['title'])
                                ->where('controller',$value['url'])
                                ->where('is_addon',1)
                                ->delete();
                            if(!empty($value['childMenu'])) {
                                foreach ($value['childMenu'] as $k => $v) {
                                    Db::name('admin_menu')
                                        ->where('title',$v['title'])
                                        ->where('controller',$v['url'])
                                        ->where('is_addon',1)
                                        ->delete();
                                }
                            }
                        }
                        $childMenu = Db::name('admin_menu')->where('parent_id',$parentId)->where('is_show',1)->find();
                        if(empty($childMenu)) {
                            Db::name('admin_menu')->where('id', $parentId)->update(['is_show' => 0]);
                        }
                    }
                    //更新ini文件
                    $info['isInstall'] = 0;
                    $info['status'] = 0;
                    $res = set_addons_info($name,$info);
                    if(!$res) {
                        // 回滚事务
                        Db::rollback();
                        return fail('更新ini文件失败');
                    } else {
                        // 提交事务
                        Db::commit();
                        Cache::clear();
                        return success();
                    }
                } else {
                    // 回滚事务
                    Db::rollback();
                    return fail('此插件未安装');
                }
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return fail($e->getMessage());
        }
    }

}