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

use app\common\model\Coupon as CouponModel;
use app\common\model\CouponUser as CouponUserModel;
use app\common\logic\BaseLogic;
use think\facade\Db;

class Coupon extends BaseLogic
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
            $couponList = [];
            $res = ['code' => 0,'msg' => 'success'];
            $state = $param['state'] ?? 0;  //状态 0:可领 1:可用 2:已用 3:失效 其他:商品可领券
            $goodsId = $param['goods_id'] ?? 0;  //商品页可领优惠券

            $userCoupon = CouponUserModel::where('user_id',$param['user_id'])
                ->order('id','desc')
                ->select()
                ->toArray();
            switch ($state)
            {
                case 0:
                    //可领
                    $getCouponId = [];
                    foreach ($userCoupon as $key => $value) {
                        if($value['is_use'] == 0 && strtotime($value['end_date']) >= time()) {
                            array_push($getCouponId, $value['coupon_id']);
                        }
                    }
                    $tCouponList = CouponModel::where('type',2)
                        ->whereColumn('send_amount','>','get_amount')
                        ->where('id','not in',$getCouponId)
                        ->where('b_date','<=',$time)
                        ->where('e_date','>=',$time)
                        ->order('cut_price','desc')
                        ->select()
                        ->toArray();
                    foreach ($tCouponList as $key => $value) {
                        $tCouponList[$key]['use_type_title'] = $value['use_type'] == 0 ? '全场通用' : '指定商品';
                        $tCouponList[$key]['b_date'] = formatDate($value['b_date'],2,2);
                        $tCouponList[$key]['e_date'] = formatDate($value['e_date'],2,2);
                        if($value['per_amount'] > 0) {
                            $getNum = CouponUserModel::where('user_id',$param['user_id'])
                                ->where('coupon_id',$value['id'])
                                ->count();
                            if($value['per_amount'] > $getNum) {
                                array_push($couponList, $tCouponList[$key]);
                            }
                        } else {
                            array_push($couponList, $tCouponList[$key]);
                        }
                    }
                    break;
                case 1:
                    //可用
                    foreach ($userCoupon as $key => $value) {
                        if($value['is_use'] == 0 && strtotime($value['end_date']) >= time()) {
                            $coupon = CouponModel::where('id',$value['coupon_id'])->find();
                            if(!empty($coupon)) {
                                $value['title'] = $coupon['title'];
                                $value['min_price'] = $coupon['min_price'];
                                $value['cut_price'] = $coupon['cut_price'];
                                $value['use_type'] = $coupon['use_type'];
                                $value['goods_ids'] = $coupon['goods_ids'];
                                $value['use_type_title'] = $coupon['use_type'] == 0 ? '全场通用' : '指定商品';
                                $value['b_date'] = formatDate($value['add_date'],2,2);
                                $value['e_date'] = formatDate($value['end_date'],2,2);
                                array_push($couponList, $value);
                            }
                        }
                    }
                    break;
                case 2:
                    //已用
                    foreach ($userCoupon as $key => $value) {
                        if($value['is_use'] == 1) {
                            $coupon = CouponModel::where('id',$value['coupon_id'])->find();
                            if(!empty($coupon)) {
                                $value['title'] = $coupon['title'];
                                $value['min_price'] = $coupon['min_price'];
                                $value['cut_price'] = $coupon['cut_price'];
                                $value['use_type_title'] = $coupon['use_type'] == 0 ? '全场通用' : '指定商品';
                                $value['b_date'] = formatDate($value['add_date'],2,2);
                                $value['e_date'] = formatDate($value['end_date'],2,2);
                                array_push($couponList, $value);
                            }
                        }
                    }
                    break;
                case 3:
                    //失效
                    foreach ($userCoupon as $key => $value) {
                        if(strtotime($value['end_date']) < time()) {
                            $coupon = CouponModel::where('id',$value['coupon_id'])->find();
                            if(!empty($coupon)) {
                                $value['title'] = $coupon['title'];
                                $value['min_price'] = $coupon['min_price'];
                                $value['cut_price'] = $coupon['cut_price'];
                                $value['use_type_title'] = $coupon['use_type'] == 0 ? '全场通用' : '指定商品';
                                $value['b_date'] = formatDate($value['add_date'],2,2);
                                $value['e_date'] = formatDate($value['end_date'],2,2);
                                array_push($couponList, $value);
                            }
                        }
                    }
                    break;
                default:
                    //商品页优惠券
                    $couponList = CouponModel::where('type',2)
                        ->whereColumn('send_amount','>','get_amount')
                        ->whereRaw('use_type = 0 or (use_type = 1 and FIND_IN_SET('.$goodsId.',goods_ids))')
                        ->order('cut_price','desc')
                        ->select()
                        ->toArray();
                    foreach ($couponList as $key => $value) {
                        $couponList[$key]['use_type_title'] = $value['use_type'] == 0 ? '全场通用' : '指定商品';
                        $couponList[$key]['is_get'] = 1;
                        $couponList[$key]['min_price1'] = intval($value['min_price']);
                        $couponList[$key]['cut_price1'] = intval($value['cut_price']);
                        $couponUser = CouponUserModel::where('user_id', $param['user_id'])
                            ->where('coupon_id',$value['id'])
                            ->where('is_use',0)
                            ->where('add_date','<=',time())
                            ->where('end_date','>=',time())
                            ->find();
                        if(empty($couponUser)) {
                            $couponList[$key]['is_get'] = 0;
                        }
                    }
            }
            $res['data'] = $couponList;
            return $res;
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 下单获取最大可用优惠券
     * @param array $goodsList
     * @param int $userId
     * @return array
     */
    public function getMaxCoupon(array $goodsList = [],int $userId = 0) : array
    {
        $data = [];
        $userCoupon = $this->readList(['state' => 1,'user_id' => $userId]);
        $userCoupon = $userCoupon['code'] == 0 ? $userCoupon['data'] : [];
        if(!empty($userCoupon)) {
            //优惠券列表按照优惠金额从大到小排列
            //$userCoupon = arraySort($userCoupon,'cut_price');
            $cutPrice = 0;
            foreach ($userCoupon as $value) {
                if($value['use_type'] == 0) {
                    //所有商品
                    $totalPrice = 0;
                    foreach ($goodsList as $v) {
                        $totalPrice = $totalPrice + $v['total_price'];
                    }
                    if($totalPrice >= $value['min_price']) {
                        //可用
                        if($value['cut_price'] > $cutPrice) {
                            $data = ['id' => $value['id'], 'price' => $value['cut_price'], 'goods_ids' => []];
                            $cutPrice = $value['cut_price'];
                        }
                    }
                } else {
                    //指定商品
                    $goodsIds = explode(',',$value['goods_ids']);
                    $totalPrice = 0;
                    $goodsStr = [];
                    foreach ($goodsList as $v) {
                        if(in_array($v['id'],$goodsIds)) {
                            $totalPrice = $totalPrice + $v['total_price'];
                            array_push($goodsStr, $v['id']);
                        }
                    }
                    if($totalPrice >= $value['min_price']) {
                        //可用
                        if($value['cut_price'] > $cutPrice) {
                            $data = ['id' => $value['id'],'price' => $value['cut_price'],'goods_ids' => $goodsStr];
                            $cutPrice = $value['cut_price'];
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 保存数据
     * @param array $param
     * @return array
     */
    public function saveData(array $param = []) : array
    {
        try {
            $time = time();
            $coupon = CouponModel::where('uuid',$param['uuid'])
                ->where('type',2)
                ->where('b_date','<=',$time)
                ->where('e_date','>=',$time)
                ->whereColumn('send_amount','>','get_amount')
                ->find();
            if(!empty($coupon)) {
                if($coupon['per_amount'] == 0) {
                    $couponUser = CouponUserModel::where('user_id', $param['user_id'])
                        ->where('coupon_id',$coupon['id'])
                        ->where('is_use',0)
                        ->where('add_date','<=',$time)
                        ->where('end_date','>=',$time)
                        ->find();
                    if(!empty($couponUser)) {
                        return fail('您已领取过了，请使用后再领。');
                    }
                } else {
                    $getNum = CouponUserModel::where('user_id', $param['user_id'])
                        ->where('coupon_id',$coupon['id'])
                        ->count();
                    if($getNum >= $coupon['per_amount']) {
                        return fail('您已领取过了，不能再领。');
                    }
                }
                //发券
                $update = Db::name('coupon')
                    ->where('id', $coupon['id'])
                    ->where('version', $coupon['version'])
                    ->inc('get_amount')
                    ->inc('version')
                    ->update();
                if ($update > 0) {
                    $end_date = $coupon['use_time'] > 0 ? $time + $coupon['use_time'] : strtotime($coupon['e_date']);
                    CouponUserModel::create([
                        'user_id' => $param['user_id'],
                        'coupon_id' => $coupon['id'],
                        'coupon_sn' => makeOrderSn('C'),
                        'add_date' => $time,
                        'end_date' => $end_date,
                    ]);
                } else {
                    //并发重新提交
                    $this->saveData($param);
                }

            } else {
                return fail('优惠券不存在或已领完');
            }
            return ['code' => 0,'msg' => 'success'];
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

}