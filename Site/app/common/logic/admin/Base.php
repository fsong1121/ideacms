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

use app\common\logic\BaseLogic;
use think\facade\Db;

class Base extends BaseLogic
{
    /**
     * 根据uuid判断是否具有访问当前页面的权限
     * @param string $uuid
     * @param string $controller 当前控制器
     * @param string $action 当前方法
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function checkRoleAuth(string $uuid = '', string $controller = '', string $action = '') : array
    {
        $allController = Db::name('admin_menu')
            ->where('controller', '<>', '')
            ->where('is_show', 1)
            ->column('controller');
        foreach ($allController as $key => $value) {
            $controllerArr = explode('/',$value);
            if(count($controllerArr) > 1) {
                $allController[$key] = $controllerArr[1];
            }
        }
        $roleId = Db::name('admin')
            ->where('uuid', $uuid)
            ->value('role_id');
        if ($roleId > 0 && in_array($controller, $allController)) {
            $roleList = [];
            $rolePower = Db::name('admin_role')
                ->where('id', $roleId)
                ->value('power');
            $roleMenu = Db::name('admin_menu')
                ->where('id', 'in', $rolePower)
                ->select()
                ->toArray();
            foreach ($roleMenu as $value) {
                $menuController = $value['controller'];
                if($value['is_addon'] == 1) {
                    $menuController = explode('/',$value['controller'])[1];
                }
                $roleStr = $menuController;
                if (empty($value['operation'])) {
                    $roleStr = $roleStr . '/index';
                } else {
                    $roleStr = $roleStr . '/' . $value['operation'];
                }
                //其他允许方法
                if (!empty($value['other_operation'])) {
                    $operationArr = explode(',', $value['other_operation']);
                    foreach ($operationArr as $k => $v) {
                        array_push($roleList, $menuController . '/' . $v);
                    }
                }
                array_push($roleList, $roleStr);
            }
            if (!in_array($controller . '/' . $action, $roleList)) {
                return fail();
            }
        }
        return success();
    }
}