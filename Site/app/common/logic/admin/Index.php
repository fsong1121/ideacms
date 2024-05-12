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

use think\facade\Db;

class Index extends Base
{
    /**
     * 根据管理员UUID获取后台菜单
     * @param string $uuid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminMenu(string $uuid = '') : array
    {
        $menu = [];
        $rolePower = [];
        $data = Db::name('admin')->where('uuid',$uuid)->findOrEmpty();
        if(!empty($data)) {
            //获取有操作权限的菜单ID
            $roleId = $data['role_id'];
            if($roleId > 0) {
                $rolePower = explode(',',Db::name('admin_role')->where('id',$roleId)->value('power'));
                $parentList = [];
                foreach ($rolePower as $value) {
                    //把上级菜单加入
                    $parentId = Db::name('admin_menu')->where('id', $value)->value('parent_id');
                    if (!in_array($parentId, $parentList)) {
                        array_push($parentList, $parentId);
                    }
                    if($parentId > 0) {
                        //把上上级菜单加入
                        $parentId1 = Db::name('admin_menu')->where('id', $parentId)->value('parent_id');
                        if (!in_array($parentId1, $parentList)) {
                            array_push($parentList, $parentId1);
                        }
                    }
                }
                $rolePower = array_merge($rolePower,$parentList);
            }
            //一级菜单
            $menu = $this->getCat(0,'admin_menu',1,0);
            foreach ($menu as $k1 => $v1) {
                //删除没有权限的一级菜单
                if($roleId > 0 && !in_array($v1['id'],$rolePower)) {
                    unset($menu[$k1]);
                } else {
                    //二级菜单
                    $menu[$k1]['childMenus'] = $this->getCat($v1['id'],'admin_menu',1,0);
                    foreach ($menu[$k1]['childMenus'] as $k2 => $v2) {
                        //删除没有权限的二级菜单
                        if($roleId > 0 && !in_array($v2['id'],$rolePower)) {
                            unset($menu[$k1]['childMenus'][$k2]);
                        } else {
                            //三级菜单
                            $menu[$k1]['childMenus'][$k2]['childMenus'] = $this->getCat($v2['id'],'admin_menu',1,0);
                            foreach ($menu[$k1]['childMenus'][$k2]['childMenus'] as $k3 => $v3) {
                                //删除没有权限的三级菜单
                                if($roleId > 0 && !in_array($v3['id'],$rolePower)) {
                                    unset($menu[$k1]['childMenus'][$k2]['childMenus'][$k3]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $menu;
    }
}