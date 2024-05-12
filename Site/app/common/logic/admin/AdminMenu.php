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

use app\common\model\AdminMenu as AdminMenuModel;

class AdminMenu extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return AdminMenuModel|array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        return AdminMenuModel::find($id);
    }

    /**
     * 保存数据
     * @param $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveData($param) : array
    {
        $list = new AdminMenuModel();
        if(isset($param['m_id'])) {
            $list = AdminMenuModel::find($param['m_id']);
        }
        try {
            $menu = AdminMenuModel::where('id',$param['m_parent_id'])->find();
            $deep = empty($menu) ? 1 : $menu['deep'] + 1;
            $data = [
                'parent_id' => $param['m_parent_id'],
                'title' => $param['m_title'],
                'subtitle' => $param['m_subtitle'],
                'controller' => $param['m_controller'],
                'operation' => $param['m_operation'],
                'other_operation' => $param['m_other_operation'],
                'ico' => $param['m_ico'],
                'deep' => $deep,
                'sequence' => $param['m_px'],
                'type' => $param['m_type'],
                'is_show' => $param['m_show']
            ];
            if(isset($param['m_id'])) { //如果是编辑
                $first_child = AdminMenuModel::where('parent_id',$param['m_id'])->column('id');
                if(!empty($first_child)) {
                    $deep1 = $deep + 1;
                    AdminMenuModel::update(['deep'=>$deep1],['parent_id'=>$param['m_id']]);
                    $second_child = AdminMenuModel::where('parent_id','in',$first_child)->column('id');
                    if(!empty($second_child)) {
                        $deep2 = $deep1 + 1;
                        AdminMenuModel::update(['deep'=>$deep2],['parent_id'=>$first_child]);
                    }
                }
            }
            $list->save($data);
            $this->setCat('admin_menu');
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 删除
     * @param $ids
     * @return array
     */
    public function delData($ids) : array
    {
        try {
            $adminMenu = AdminMenuModel::where('id',$ids)->findOrEmpty();
            if(!$adminMenu->isEmpty()) {
                if($adminMenu['type'] == 0) {
                    return fail('系统菜单不可删除');
                }
                else {
                    //最多支持三级
                    $menu = AdminMenuModel::where('parent_id',$ids)->select();
                    foreach ($menu as $value) {
                        AdminMenuModel::where('parent_id',$value['id'])->delete();
                        AdminMenuModel::destroy($value);
                    }
                    AdminMenuModel::destroy($ids);
                }
            }
            $this->setCat('admin_menu');
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

}