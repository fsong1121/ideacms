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

use app\common\model\Discount as DiscountModel;
use think\facade\Db;

class Discount extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return DiscountModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        return DiscountModel::where('id',$id)->find();
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = DiscountModel::where('id', '>', '0');
            if($param['keys'] != '') {
                $list = $list->where('title','like','%'.$param['keys'].'%');
            }
            $list = $list->order(['id'=>'desc'])
                ->paginate($param['limit'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $time = time();
                if($time < strtotime($value['b_date'])){
                    $list['data'][$key]['state'] = 0; //未开始
                }
                else if($time >= strtotime($value['b_date']) && $time <= strtotime($value['e_date'])){
                    $list['data'][$key]['state'] = 1; //活动中
                }
                else {
                    $list['data'][$key]['state'] = -1; //已过期
                }
            }
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
        $list = new DiscountModel();
        if(isset($param['m_id'])) {
            $list = DiscountModel::where('id',$param['m_id'])->find();
        }
        try {
            $param['m_send_price'] = $param['m_send_type'] == 0 ? $param['m_send_price'] : 0;
            $param['m_send_rebate'] = $param['m_send_type'] == 1 ? $param['m_send_rebate'] : 0;
            $param['m_send_integral'] = $param['m_send_type'] == 2 ? $param['m_send_integral'] : 0;
            $param['m_send_coupon_id'] = $param['m_send_type'] == 3 ? $param['m_send_coupon_id'] : 0;
            $data = [
                'title' => $param['m_title'],
                'type' => $param['m_type'],
                'min_price' => $param['m_min_price'],
                'send_type' => $param['m_send_type'],
                'send_price' => $param['m_send_price'],
                'send_rebate' => $param['m_send_rebate'],
                'send_integral' => $param['m_send_integral'],
                'send_coupon_id' => $param['m_send_coupon_id'],
                'use_type' => $param['m_use_type'],
                'goods_ids' => $param['m_goods_ids'],
                'b_date' => $param['b_date'],
                'e_date' => $param['e_date']
            ];
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
            DiscountModel::destroy($ids);
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 获取可用优惠券(内部券)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCouponList() : array
    {
        $time = time();
        return Db::name('coupon')
            ->where('type',1)
            ->where('b_date','<=',$time)
            ->where('e_date','>=',$time)
            ->whereColumn('send_amount','>','get_amount')
            ->select()
            ->toArray();
    }

    /**
     * 获取商品列表
     * @param $ids
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGoodsList($ids) : array
    {
        return Db::name('goods')
            ->where('id','in',$ids)
            ->field('id,pic,title')
            ->select()
            ->toArray();
    }

}