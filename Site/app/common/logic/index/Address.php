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
namespace app\common\logic\index;

use app\common\logic\BaseLogic;
use app\common\model\UserAddress as UserAddressModel;
use think\facade\Db;

class Address extends BaseLogic
{
    /**
     * 读取数据
     * @param array $param
     * @return array
     */
    public function readData(array $param = []) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            if(!empty($param['id'])) {
                $data = UserAddressModel::where('user_id',$param['user_id'])
                    ->where('id',$param['id'])
                    ->find();
            } else {
                $data = UserAddressModel::where('user_id',$param['user_id'])
                    ->order(['is_default'=>'desc','id'=>'desc'])
                    ->find();
            }
            if(!empty($data)) {
                $res['data'] = $data;
            } else {
                $res['code'] = 500;
                $res['msg'] = '地址不存在';
            }
            return $res;
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = UserAddressModel::where('user_id',$param['user_id'])
                ->order(['is_default'=>'desc','id'=>'desc'])
                ->paginate($param['size'])
                ->toArray();
            return [
                'code' => 0,
                'msg' => 'success',
                'count' => $list['total'],
                'data' => $list['data']
            ];
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存数据
     * @param array $param
     * @return array
     */
    public function saveData(array $param = []) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $userId = $param['user_id'];
            $data = [
                'user_id' => $userId,
                'name' => $param['name'],
                'tel' => $param['tel'],
                'province' => $param['province'],
                'city' => $param['city'],
                'county' => $param['county'],
                'address' => $param['address'],
                'is_default' => $param['is_default'],
            ];
            if(!isset($param['m_id'])) {
                //新增
                $address = UserAddressModel::create($data);
                if($param['is_default'] == 1) {
                    UserAddressModel::where('user_id',$userId)
                        ->where('id','<>',$address->id)
                        ->save(['is_default' => 0]);
                }
            } else {
                //编辑
                UserAddressModel::update($data,['id' => $param['m_id'],'user_id' => $userId]);
                if($param['is_default'] == 1) {
                    UserAddressModel::where('user_id',$userId)
                        ->where('id','<>',$param['m_id'])
                        ->save(['is_default' => 0]);
                }
            }
            return $res;
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 设为默认
     * @param array $param
     * @return array
     */
    public function setDefault(array $param = []) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            UserAddressModel::update(['is_default' => 1],['id' => $param['id'],'user_id' => $param['user_id']]);
            UserAddressModel::where('user_id',$param['user_id'])
                ->where('id','<>',$param['id'])
                ->save(['is_default' => 0]);
            return $res;
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 删除
     * @param array $param
     * @return array
     */
    public function delData(array $param = []) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $ids = explode(",", $param['id']);
            UserAddressModel::where('user_id',$param['user_id'])
                ->where('id','in',$ids)
                ->delete();
            return $res;
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }
}