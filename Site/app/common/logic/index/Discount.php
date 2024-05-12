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

use app\common\model\Discount as DiscountModel;
use app\common\logic\BaseLogic;
use think\facade\Db;

class Discount extends BaseLogic
{
    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $time = time();
            $res = ['code' => 0,'msg' => 'success'];
            $goodsId = $param['goods_id'] ?? 0;  //商品页
            $list = DiscountModel::where('b_date','<=',$time)
                ->where('e_date','>=',$time);
            if(!empty($goodsId)) {
                $list = $list->whereRaw('use_type = 0 OR (use_type = 1 AND FIND_IN_SET('.$goodsId.',goods_ids))');
            }
            $list = $list->select()->toArray();
            $res['data'] = $list;
            return $res;
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 下单获取订单满减(满减，满送积分均获取最大的一项，送券会叠加)
     * @param array $goodsList
     * @return array
     */
    public function getMaxDiscount(array $goodsList = []) : array
    {
        $data = [
            'price' => 0,
            'integral' => 0,
            'coupon_ids' => ''
        ];
        $discountList = [];
        $res = $this->readList();
        if($res['code'] == 0) {
            $discountList = $res['data'];
        }
        if(!empty($discountList)) {
            $sendPrice = 0;
            $sendIntegral = 0;
            $sendCouponIds = [];
            foreach ($discountList as $value) {
                if($value['use_type'] == 0) {
                    //所有商品
                    $totalPrice = 0;
                    $totalAmount = 0;
                    foreach ($goodsList as $v) {
                        $totalPrice = $totalPrice + $v['total_price'];
                        $totalAmount = $totalAmount + $v['amount'];
                    }
                } else {
                    //指定商品
                    $goodsIds = explode(',',$value['goods_ids']);
                    $totalPrice = 0;
                    $totalAmount = 0;
                    foreach ($goodsList as $v) {
                        if(in_array($v['id'],$goodsIds)) {
                            $totalPrice = $totalPrice + $v['total_price'];
                            $totalAmount = $totalAmount + $v['amount'];
                        }
                    }
                }
                //按金额或按件获取满减送
                if(($value['type'] == 0 && $totalPrice >= $value['min_price']) || ($value['type'] == 1 && $totalAmount >= $value['min_price'])) {
                    if($value['send_type'] == 0) {
                        //直减
                        if ($value['send_price'] > $sendPrice) {
                            $data['price'] = $value['send_price'];
                            $sendPrice = $value['send_price'];
                        }
                    }
                    if($value['send_type'] == 1) {
                        //折扣
                        $rebatePrice = formatPrice($totalPrice * (10 - $value['send_rebate']) / 10);
                        if ($rebatePrice > $sendPrice) {
                            $data['price'] = $rebatePrice;
                            $sendPrice = $rebatePrice;
                        }
                    }
                    if($value['send_type'] == 2) {
                        //送积分
                        if ($value['send_integral'] > $sendIntegral) {
                            $data['integral'] = $value['send_integral'];
                            $sendIntegral = $value['send_integral'];
                        }
                    }
                    if($value['send_type'] == 3) {
                        //送优惠券
                        if (!in_array($value['send_coupon_id'], $sendCouponIds)) {
                            // 如果值不存在于数组中，则添加该值
                            array_push($sendCouponIds, $value['send_coupon_id']);
                            $data['coupon_ids'] = implode(',',$sendCouponIds);
                        }
                    }
                }
            }
        }
        return $data;
    }

}