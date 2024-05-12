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

use app\common\model\Cash as CashModel;
use think\facade\Db;

class Cash extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        return CashModel::find($id);
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = CashModel::where('id', '>', '0');
            if($param['keys'] != '') {
                $list = $list->where('user_id', 'in' , Db::name('user')->where('uid|mobile|nickname', 'like' , '%' . $param['keys'] . '%')->column('id'));
            }
            if($param['k2'] != '') {
                $list = $list->where('cash_state',$param['k2']);
            }
            if($param['k3'] != '' && $param['k4'] != ''){
                $list = $list->where('add_date','>=',strtotime($param['k3']))->where('add_date','<=',strtotime($param['k4']));
            }
            $list = $list->order('id','desc')
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
     * 提现成功
     * @param $param
     * @return array
     */
    public function saveAgree($param) : array
    {
        try {
            CashModel::update(['cash_state' => 1,'cash_date' => time()],['id' => $param['m_id'],'cash_state' => 0]);
            return success();
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 提现失败
     * @param $param
     * @return array
     */
    public function saveCash($param) : array
    {
        try {
            $cash = CashModel::where('id',$param['m_id'])
                ->where('cash_state',0)
                ->find();
            if(!empty($cash)) {
                $userId = $cash->getData('user_id');
                $fee = $cash['fee'] + $cash['service_fee'];
                $cash->cash_state = -1;
                $cash->cash_info = $param['m_info'];
                $cash->cash_date = time();
                $cash->save();
                //失败要返还佣金
                $res = self::saveUserAccount($userId,$fee,2,'提现失败:' . $param['m_info']);
                if($res['code'] > 0) {
                    return fail($res['msg']);
                }
                return success();
            } else {
                return fail('无效操作被禁止');
            }
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }
}