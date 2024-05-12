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

use app\common\model\Admin as AdminModel;
use app\common\model\AdminRole as AdminRoleModel;

class Admin extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return AdminModel|array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        return AdminModel::find($id);
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = AdminModel::where('id', '>', '0');
            if($param['keys'] != '') {
                $list = $list->where('uid','like','%'.$param['keys'].'%');
            }
            $list = $list->order('id', 'desc')
                ->paginate($param['limit'])
                ->toArray();
            return [
                'code' => 0,
                'msg' => '',
                'count' => $list['total'],
                'data' => $list['data']
            ];
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
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
        $list = new AdminModel();
        if(isset($param['m_id'])) {
            $list = AdminModel::find($param['m_id']);
        }
        try {
            $data = [
                'uid' => $param['m_uid'],
                'role_id' => $param['m_role'],
                'is_work' => $param['m_work']
            ];
            //如果是新增
            if(!isset($param['m_id'])) {
                $data['uuid'] = makeUuid();
                $data['add_date'] = time();
            }
            if(!empty($param['m_pwd'])){
                $data['pwd'] = $param['m_pwd'];
            }
            $list->save($data);
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
        $ids = explode(",", $ids);
        try {
            AdminModel::destroy($ids);
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 获取角色列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRoleList() : array
    {
        return AdminRoleModel::where('id','>','0')->select()->toArray();
    }

}