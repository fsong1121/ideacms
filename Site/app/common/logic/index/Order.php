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
use app\common\model\Order as OrderModel;
use app\common\model\OrderGoods as OrderGoodsModel;
use app\common\model\OrderRefund as OrderRefundModel;
use app\common\model\Cart as CartModel;
use app\common\logic\index\Coupon as CouponLogic;
use app\common\logic\index\Discount as DiscountLogic;
use think\facade\Event;
use think\facade\Cache;
use think\facade\Session;
use think\facade\Db;
use think\api\Client;

class Order extends BaseLogic
{
    /**
     * 获取会员订单数量
     * @param array $param
     * @return array
     */
    public function getStateNumber(array $param = []) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $userId = $param['user_id'];
            $commentOrderIds = OrderModel::where('user_id',$userId)->where('activity_state',1)->where('order_state',4)->column('id');
            $data[0] = OrderModel::where('user_id',$userId)->where('activity_state',1)->where('order_state',1)->count();
            $data[1] = OrderModel::where('user_id',$userId)->where('activity_state',1)->where('order_state',2)->count();
            $data[2] = OrderModel::where('user_id',$userId)->where('activity_state',1)->where('order_state',3)->count();
            $data[3] = OrderGoodsModel::where('order_id','in',$commentOrderIds)->where('is_comment',0)->count();
            $data[4] = OrderRefundModel::where('user_id',$userId)->count();
            $res['data'] = $data;
            return $res;
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }
    /**
     * 读取数据
     * @param array $param
     * @return array
     */
    public function readData(array $param = []) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $data = OrderModel::where('user_id',$param['user_id']);
            if(isset($param['id'])) {
                $data = $data->where('id', $param['id']);
            }
            if(isset($param['sn'])) {
                $data = $data->where('order_sn', $param['sn']);
            }
            $data = $data->find();
            if(!empty($data)) {
                $orderState = $data->getData('order_state');
                $goodsList = OrderGoodsModel::where('order_id',$data['id'])
                    ->field('id,goods_id,amount,price,spec_key_name,exchange_integral')
                    ->select()
                    ->toArray();
                foreach ($goodsList as $k => $v) {
                    $goods = getGoodsInfo($v['goods_id'],'','title,pic');
                    $goodsList[$k]['goods_pic'] = getPic($goods['pic']);
                    $goodsList[$k]['goods_title'] = $goods['title'];
                    $goodsUrl = '/pages/goods/detail?id=' . $v['goods_id'];
                    if(!empty($data['order_type'])) {
                        $goodsUrl = '/pages/addons/' . $data['order_type'] . '/goodsDetail?id=' . $data['activity_id'];
                    }
                    $goodsList[$k]['goods_url'] = $goodsUrl;
                }
                $data['goods_list'] = $goodsList;
                $data['order_state_no'] = $orderState;
                $data['close_time'] = 0;
                if($orderState == 1) {
                    $data['close_time'] = ($data->getData('add_date') + config('shop.close_time') * 60 - time()) * 1000;
                }
                $data['is_refund'] = 0;
                if(config('shop.is_refund') == 1 && $orderState > 1) {
                    $data['is_refund'] = 1;
                    if($orderState > 2 && time() - strtotime($data['express_date']) > config('shop.refund_time') * 86400) {
                        //过了退换期
                        $data['is_refund'] = 0;
                    }
                }
                $data['order_type_title'] = config('order.order_type')[$data['order_type']];
                $res['data'] = $data;
            } else {
                $res['code'] = 500;
                $res['msg'] = '订单不存在';
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
            $list = OrderModel::where('user_id', $param['user_id']);
            if (isset($param['keys'])) {
                $list = $list->where('order_sn', 'like', '%' . $param['keys'] . '%');
            }
            //是否成功，比如拼团和助力订单
            if (isset($param['activity_state'])) {
                $list = $list->where('activity_state', $param['activity_state']);
            } else {
                $list = $list->where('activity_state', 1);
            }
            //订单状态
            if (isset($param['state']) && $param['state'] > 0) {
                $list = $list->where('order_state', $param['state']);
            }
            $list = $list->order('id', 'desc')
                ->paginate($param['size'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $orderGoods = OrderGoodsModel::where('order_id',$value['id'])
                    ->field('id,goods_id,amount,price,spec_key_name,exchange_integral')
                    ->select()
                    ->toArray();
                foreach ($orderGoods as $k => $v) {
                    $goods = getGoodsInfo($v['goods_id'],'','title,pic');
                    $orderGoods[$k]['goods_pic'] = getPic($goods['pic']);
                    $orderGoods[$k]['goods_title'] = $goods['title'];
                    $orderGoods[$k]['goods_integral'] = $v['exchange_integral'] / $v['amount'];
                }
                $list['data'][$key]['goods_list'] = $orderGoods;
                $orderState = Db::name('order')
                    ->where('id',$value['id'])
                    ->value('order_state');
                $list['data'][$key]['order_state_no'] = $orderState;
                $list['data'][$key]['is_refund'] = 0;
                if(config('shop.is_refund') == 1 && $orderState > 1) {
                    $list['data'][$key]['is_refund'] = 1;
                    if($orderState > 2 && time() - strtotime($list['data'][$key]['express_date']) > config('shop.refund_time') * 86400) {
                        //过了退换期
                        $list['data'][$key]['is_refund'] = 0;
                    }
                }
                $list['data'][$key]['order_type_title'] = str_replace('订单','',config('order.order_type')[$list['data'][$key]['order_type']]);
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
     * 确认订单
     * @param array $param
     * @return array
     */
    public function fillData(array $param) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $userId = $param['user_id'];
            $buyType = $param['buy_type'] ?? 0;
            $orderType = $param['order_type'] ?? '';  //如果是活动订单，价格根据activityId设置的优惠计算
            $activityId = $param['activity_id'] ?? 0;
            $groupId = $param['group_id'] ?? 0;       //团长ID
            $goodsId = $param['goods_id'] ?? 0;
            $specKey = $param['spec_key'] ?? '';
            $amount = $param['amount'] ?? 1;
            $cartIds = $param['cart_id'] ?? [];
            $goodsTotalPrice = 0;  //商品金额
            $totalCommission = 0;  //订单佣金
            $totalIntegral = 0;    //订单积分
            $totalGrowth = 0;      //订单成长值
            $sendPrice = 0;        //运费
            $rebatePrice = 0;      //会员折扣
            $discountPrice = 0;    //满减优惠
            $couponPrice = 0;      //优惠券抵扣
            $freeTotal = 0;        //参与满额包邮总金额
            $weightTotal = 0;      //总重量
            $volumeTotal = 0;      //总体积
            $userRebate = getUserLevel($userId)['rebate'] / 100;
            $goodsList = [];
            if($buyType == 0) {
                //直接购买
                $goods = getGoodsInfo($goodsId,$specKey,'id,title,pic,type,is_sale,is_delete,express_type,express_price,express_template_id,is_full_free,commission,integral,growth');
                if(!empty($goods) && $goods['is_sale'] == 1 && $goods['is_delete'] == 0) {
                    $amount = $amount > $goods['stock'] ? $goods['stock'] : $amount;
                    $goodsTotalPrice = $goodsTotalPrice + $amount * $goods['price'];
                    $totalCommission = $totalCommission + $amount * $goods['commission'];
                    $totalIntegral = $totalIntegral + $amount * $goods['integral'];
                    $totalGrowth = $totalGrowth + $amount * $goods['growth'];
                    $goodsList[0]['id'] = $goods['id'];
                    $goodsList[0]['title'] = $goods['title'];
                    $goodsList[0]['pic'] = getPic($goods['pic']);
                    $goodsList[0]['price'] = $goods['price'];
                    $goodsList[0]['amount'] = $amount;
                    $goodsList[0]['goods_id'] = $goodsId;
                    $goodsList[0]['spec_key'] = $specKey;
                    $goodsList[0]['spec_key_name'] = $goods['spec_key_name'];
                    $goodsList[0]['rebate_price'] = formatPrice($amount * $goods['price'] * (1 - $userRebate));
                    $goodsList[0]['commission'] = $amount * $goods['commission'];
                    $goodsList[0]['integral'] = $amount * $goods['integral'];
                    $goodsList[0]['growth'] = $amount * $goods['growth'];
                    $goodsList[0]['total_price'] = $amount * $goods['price'];
                    $weightTotal = $amount * $goods['weight'];
                    $volumeTotal = $amount * $goods['volume'];
                    if($goods['is_full_free'] == 1) {
                        $freeTotal = $amount * $goods['price'];
                    }
                    //运费(普通商品才计算运费)
                    $province = $param['province'] ?? '';
                    if($goods['type'] == 0 && !empty($province)) {
                        if ($goods['express_type'] == 1) {
                            $sendPrice = $sendPrice + $goods['express_price'];
                        }
                        if ($goods['express_type'] == 2) {
                            //模板运费
                            $expressTemplate = Db::name('express_template')
                                ->where('id',$goods['express_template_id'])
                                ->find();
                            if(!empty($expressTemplate)) {
                                $expressTemplatePrice = Db::name('express_template_price')
                                    ->whereFindInSet('area_names',$province)
                                    ->find();
                                $firstNum = empty($expressTemplatePrice) ? $expressTemplate['first_num'] : $expressTemplatePrice['first_num'];
                                $firstPrice = empty($expressTemplatePrice) ? $expressTemplate['first_price'] : $expressTemplatePrice['first_price'];
                                $secondNum = empty($expressTemplatePrice) ? $expressTemplate['second_num'] : $expressTemplatePrice['second_num'];
                                $secondPrice = empty($expressTemplatePrice) ? $expressTemplate['second_price'] : $expressTemplatePrice['second_price'];
                                if($expressTemplate['type'] == 0) {
                                    //按重量
                                    if($weightTotal > $firstNum * 1000) {
                                        $sendPrice = $firstPrice + ceil(($weightTotal / 1000 - $firstNum) / $secondNum) * $secondPrice;
                                    } else {
                                        $sendPrice = $firstPrice;
                                    }
                                }
                                if($expressTemplate['type'] == 1) {
                                    //按体积
                                    if($volumeTotal > $firstNum) {
                                        $sendPrice = $firstPrice + ceil(($volumeTotal - $firstNum) / $secondNum) * $secondPrice;
                                    } else {
                                        $sendPrice = $firstPrice;
                                    }
                                }
                                if($expressTemplate['type'] == 2) {
                                    //按件数
                                    if($amount > $firstNum) {
                                        $sendPrice = $firstPrice + ceil(($amount - $firstNum) / $secondNum) * $secondPrice;
                                    } else {
                                        $sendPrice = $firstPrice;
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                //购物车提交
                $cart = CartModel::where('user_id',$userId)
                    ->where('id','in',$cartIds)
                    ->select()
                    ->toArray();
                $i = 0;
                foreach ($cart as $value) {
                    $goods = getGoodsInfo($value['goods_id'],$value['spec_key'],'id,title,pic,type,is_sale,is_delete,express_type,express_price,express_template_id,is_full_free,commission,integral,growth');
                    if(!empty($goods) && $goods['type'] == 0 && $goods['is_sale'] == 1 && $goods['is_delete'] == 0) {
                        $amount = $value['amount'] > $goods['stock'] ? $goods['stock'] : $value['amount'];
                        $goodsTotalPrice = $goodsTotalPrice + $amount * $goods['price'];
                        $totalCommission = $totalCommission + $amount * $goods['commission'];
                        $totalIntegral = $totalIntegral + $amount * $goods['integral'];
                        $totalGrowth = $totalGrowth + $amount * $goods['growth'];
                        $goodsList[$i]['id'] = $goods['id'];
                        $goodsList[$i]['title'] = $goods['title'];
                        $goodsList[$i]['pic'] = getPic($goods['pic']);
                        $goodsList[$i]['price'] = $goods['price'];
                        $goodsList[$i]['amount'] = $amount;
                        $goodsList[$i]['goods_id'] = $value['goods_id'];
                        $goodsList[$i]['spec_key'] = $value['spec_key'];
                        $goodsList[$i]['spec_key_name'] = $goods['spec_key_name'];
                        $goodsList[$i]['rebate_price'] = formatPrice($amount * $goods['price'] * (1 - $userRebate));
                        $goodsList[$i]['commission'] = $amount * $goods['commission'];
                        $goodsList[$i]['integral'] = $amount * $goods['integral'];
                        $goodsList[$i]['growth'] = $amount * $goods['growth'];
                        $goodsList[$i]['total_price'] = $amount * $goods['price'];
                        $weightTotal = $amount * $goods['weight'];
                        $volumeTotal = $amount * $goods['volume'];
                        if($goods['is_full_free'] == 1) {
                            $freeTotal = $freeTotal + $amount * $goods['price'];
                        }
                        //运费(普通商品才计算运费)
                        $province = $param['province'] ?? '';
                        if($goods['type'] == 0 && !empty($province)) {
                            if ($goods['express_type'] == 1) {
                                $sendPrice = $sendPrice + $goods['express_price'];
                            }
                            if ($goods['express_type'] == 2) {
                                //模板运费
                                $expressTemplate = Db::name('express_template')
                                    ->where('id',$goods['express_template_id'])
                                    ->find();
                                if(!empty($expressTemplate)) {
                                    $expressTemplatePrice = Db::name('express_template_price')
                                        ->whereFindInSet('area_names',$province)
                                        ->find();
                                    $firstNum = empty($expressTemplatePrice) ? $expressTemplate['first_num'] : $expressTemplatePrice['first_num'];
                                    $firstPrice = empty($expressTemplatePrice) ? $expressTemplate['first_price'] : $expressTemplatePrice['first_price'];
                                    $secondNum = empty($expressTemplatePrice) ? $expressTemplate['second_num'] : $expressTemplatePrice['second_num'];
                                    $secondPrice = empty($expressTemplatePrice) ? $expressTemplate['second_price'] : $expressTemplatePrice['second_price'];
                                    if($expressTemplate['type'] == 0) {
                                        //按重量
                                        if($weightTotal > $firstNum * 1000) {
                                            $sendPrice = $sendPrice + $firstPrice + ceil(($weightTotal / 1000 - $firstNum) / $secondNum) * $secondPrice;
                                        } else {
                                            $sendPrice = $sendPrice + $firstPrice;
                                        }
                                    }
                                    if($expressTemplate['type'] == 1) {
                                        //按体积
                                        if($volumeTotal > $firstNum) {
                                            $sendPrice = $sendPrice + $firstPrice + ceil(($volumeTotal - $firstNum) / $secondNum) * $secondPrice;
                                        } else {
                                            $sendPrice = $sendPrice + $firstPrice;
                                        }
                                    }
                                    if($expressTemplate['type'] == 2) {
                                        //按件数
                                        if($amount > $firstNum) {
                                            $sendPrice = $sendPrice + $firstPrice + ceil(($amount - $firstNum) / $secondNum) * $secondPrice;
                                        } else {
                                            $sendPrice = $sendPrice + $firstPrice;
                                        }
                                    }
                                }
                            }
                        }
                        $i = $i + 1;
                    }
                }
            }
            //开启了满额包邮
            if(config('shop.is_free_shipping') == 1) {
                if(config('shop.free_price') <= $freeTotal) {
                    $sendPrice = 0;
                }
            }
            //会员折扣
            $rebatePrice = $goodsTotalPrice * (1 - $userRebate);
            //自动选择最大优惠券和满减
            $res['data']['couponId'] = '';
            $logic = new CouponLogic();
            $coupon = $logic->getMaxCoupon($goodsList,$userId);
            if(!empty($coupon)) {
                $couponPrice = $coupon['price'];
                $res['data']['couponId'] = $coupon['id'];
            }
            //满减优惠
            $res['data']['discountCouponIds'] = '';
            $res['data']['discountIntegral'] = 0;
            $discountLogic = new DiscountLogic();
            $discount = $discountLogic->getMaxDiscount($goodsList);
            if(!empty($discount)) {
                $discountPrice = $discount['price'];
                $res['data']['discountCouponIds'] = $discount['coupon_ids'];
                $res['data']['discountIntegral'] = $discount['integral'];
            }

            $res['data']['goodsList'] = $goodsList;
            $res['data']['goodsPrice'] = formatPrice($goodsTotalPrice);
            $res['data']['totalCommission'] = formatPrice($totalCommission);
            $res['data']['totalIntegral'] = $totalIntegral;
            $res['data']['totalGrowth'] = $totalGrowth;
            $res['data']['sendPrice'] = formatPrice($sendPrice);
            $res['data']['rebatePrice'] = formatPrice($rebatePrice);
            $res['data']['discountPrice'] = formatPrice($discountPrice);
            $res['data']['couponPrice'] = formatPrice($couponPrice);
            $payPrice = $goodsTotalPrice + $sendPrice - $rebatePrice - $discountPrice - $couponPrice;
            $payPrice = $payPrice > 0 ? $payPrice : 0;
            $res['data']['payPrice'] = formatPrice($payPrice);

            //参数保存
            $res['data']['params'] = [
                'buyType' => $buyType,
                'orderType' => $orderType,
                'activityId' => $activityId,
                'groupId' => $groupId,
                'goodsId' => $goodsId,
                'specKey' => $specKey,
                'amount' => $amount,
                'cartIds' => $cartIds
            ];

            //积分订单
            $res1 = Event::trigger('fillIntegralData',[
                'data' => $res['data'],
                'order_type' => $orderType,
                'activity_id' => $activityId,
                'amount' => $amount
            ]);
            if(!empty($res1) && $res1[0]['code'] == 0) {
                $res['data'] = $res1[0]['data'];
                return $res;
            }
            //活动订单
            $res1 = Event::trigger('fillActivityData',[
                'data' => $res['data'],
                'order_type' => $orderType,
                'activity_id' => $activityId,
                'amount' => $amount
            ]);
            if(!empty($res1) && $res1[0]['code'] == 0) {
                $res['data'] = $res1[0]['data'];
                return $res;
            }
            //助力订单
            $res1 = Event::trigger('fillAssistData',[
                'data' => $res['data'],
                'order_type' => $orderType,
                'activity_id' => $activityId,
                'amount' => $amount
            ]);
            if(!empty($res1) && $res1[0]['code'] == 0) {
                $res['data'] = $res1[0]['data'];
                return $res;
            }
            //拼团订单
            $res1 = Event::trigger('fillCombinationData',[
                'data' => $res['data'],
                'order_type' => $orderType,
                'activity_id' => $activityId,
                'amount' => $amount
            ]);
            if(!empty($res1) && $res1[0]['code'] == 0) {
                $res['data'] = $res1[0]['data'];
                return $res;
            }
            //秒杀订单
            $res1 = Event::trigger('fillSeckillData',[
                'data' => $res['data'],
                'order_type' => $orderType,
                'activity_id' => $activityId,
                'amount' => $amount
            ]);
            if(!empty($res1) && $res1[0]['code'] == 0) {
                $res['data'] = $res1[0]['data'];
                return $res;
            }
            Session::set('order_' . $userId,$res);
            Cache::set('order_' . $userId,$res,600);
            return $res;
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
            $data = [];
            $time = time();
            $fillData = Session::get('order_' . $userId,'');
            if(empty($fillData)) {
                $fillData = Cache::get('order_' . $userId,'');
                if(empty($fillData)) {
                    return fail('订单提交失败');
                }
            }
            $fillData = $fillData['data'];

            $buyType = $fillData['params']['buyType'];            //0:直接购买 1:购物车提交
            $orderType = $fillData['params']['orderType'];        //如果是活动订单，价格根据activityId设置的优惠计算
            $activityId = $fillData['params']['activityId'];      //活动ID
            $groupId = $fillData['params']['groupId'];            //团长ID
            $goodsId = $fillData['params']['goodsId'];            //商品ID
            $specKey = $fillData['params']['specKey'];            //商品规格
            $amount = $fillData['params']['amount'];              //购买数量
            $cartIds = $fillData['params']['cartIds'];            //购物车ID
            $name = $param['name'];                               //姓名
            $tel = $param['tel'];                                 //电话
            $address = $param['address'];                         //地址
            $sendType = $param['send_type'] ?? 1;                 //配送方式
            $info = $param['info'] ?? '';                         //备注
            $terminal = $param['terminal'] ?? 1;                  //来源
            $goodsTotalPrice = $fillData['goodsPrice'];           //商品金额
            $totalCommission = $fillData['totalCommission'];      //订单佣金
            $totalIntegral = $fillData['totalIntegral'];          //订单积分
            $totalGrowth = $fillData['totalGrowth'];              //订单成长值
            $sendPrice = $fillData['sendPrice'];                  //运费
            $rebatePrice = $fillData['rebatePrice'];              //会员折扣
            $discountPrice = $fillData['discountPrice'];          //满减优惠
            $discountCouponIds = $fillData['discountCouponIds'];  //满减优惠
            $discountIntegral = $fillData['discountIntegral'];    //满减优惠
            $couponPrice = $fillData['couponPrice'];              //优惠券抵扣
            $couponId = $fillData['couponId'];                    //优惠券ID
            $exchangeIntegral = 0; //兑换积分
            $exchangePrice = 0;    //积分抵扣费用
            $activityState = 1;    //活动订单状态

            $orderSn = makeOrderSn();
            $goodsList = $fillData['goodsList'];
            // 启动事务
            Db::startTrans();
            try {
                if (!empty($goodsList)) {
                    //实付款
                    $payPrice = formatPrice($goodsTotalPrice + $sendPrice - $rebatePrice - $discountPrice - $couponPrice);
                    $payPrice = $payPrice > 0 ? $payPrice : 0;
                    $orderState = $payPrice > 0 ? 1 : 2;
                    //实付款为0的非普通商品直接发货
                    if($buyType == 0 && getGoodsInfo($goodsId,'','type')['type'] > 0 && $orderState == 2) {
                        $orderState = 3;
                        $data['express_type'] = 2;
                        $data['express_date'] = time();
                        $data['pay_date'] = time();
                    }
                    //订单数据
                    $data['order_sn'] = $orderSn;
                    $data['user_id'] = $userId;
                    $data['price'] = $goodsTotalPrice;
                    $data['pay_price'] = $payPrice;
                    $data['order_state'] = $orderState;
                    $data['terminal'] = $terminal;
                    $data['send_type'] = $sendType;
                    $data['store_id'] = 0;
                    $data['info'] = $info;
                    $data['name'] = $name;
                    $data['tel'] = $tel;
                    $data['address'] = $address;
                    $data['express_price'] = $sendPrice;
                    $data['pickup_sn'] = '';
                    $data['coupon_id'] = $couponId;
                    $data['coupon_price'] = $couponPrice;
                    $data['rebate_price'] = $rebatePrice;
                    $data['discount_price'] = $discountPrice;
                    $data['discount_integral'] = $discountIntegral;
                    $data['discount_coupon_ids'] = $discountCouponIds;
                    $data['exchange_integral'] = $exchangeIntegral;
                    $data['exchange_price'] = $exchangePrice;
                    $data['user_commission'] = $totalCommission;
                    $data['order_integral'] = $totalIntegral;
                    $data['order_growth'] = $totalGrowth;
                    $data['order_type'] = $orderType;
                    $data['activity_id'] = $activityId;
                    $data['activity_state'] = $activityState;
                    $data['add_date'] = $time;

                    //积分订单
                    $res1 = Event::trigger('integralOrder',[
                        'goods_id' => $goodsId,
                        'amount' => $amount,
                        'data' => $data,
                        'goods_list' => $goodsList
                    ]);
                    if(!empty($res1)) {
                        if($res1[0]['code'] == 0) {
                            $data = $res1[0]['data'];
                            $goodsList = $res1[0]['goods_list'];
                            $orderState = $data['order_state'];
                            $discountPrice = $data['discount_price'];
                            $couponPrice = $data['coupon_price'];
                            $exchangeIntegral = $data['exchange_integral'];
                            $exchangePrice = $data['price'];
                        } else {
                            return fail($res1[0]['msg']);
                        }
                    }

                    //添加订单
                    $orderId = Db::name('order')->insertGetId($data);
                    //添加订单明细
                    $goodsData = [];
                    foreach ($goodsList as $k => $v) {
                        $goodsRate = $goodsTotalPrice > 0 ? $v['price'] * $v['amount'] / $goodsTotalPrice : 0;
                        $goodsData[$k]['order_id'] = $orderId;
                        $goodsData[$k]['goods_id'] = $v['goods_id'];
                        $goodsData[$k]['spec_key'] = $v['spec_key'];
                        $goodsData[$k]['spec_key_name'] = $v['spec_key_name'];
                        $goodsData[$k]['price'] = $v['price'];
                        $goodsData[$k]['amount'] = $v['amount'];
                        $goodsData[$k]['rebate_price'] = $v['rebate_price'];
                        $goodsData[$k]['discount_price'] = $discountPrice * $goodsRate;
                        $goodsData[$k]['coupon_price'] = $couponPrice * $goodsRate;
                        $goodsData[$k]['exchange_integral'] = $exchangeIntegral * $goodsRate;
                        $goodsData[$k]['exchange_price'] = $exchangePrice * $goodsRate;
                        $goodsData[$k]['commission'] = $v['commission'];
                        $goodsData[$k]['integral'] = $v['integral'];
                        $goodsData[$k]['growth'] = $v['growth'];
                        $goodsData[$k]['add_date'] = time();
                    }
                    Db::name('order_goods')->insertAll($goodsData);
                    //加销量减库存等
                    foreach ($goodsData as $k => $v) {
                        $goods = Db::name('goods')->where('id', $v['goods_id'])->find();
                        $update = Db::name('goods')
                            ->where('id', $goods['id'])
                            ->where('version', $goods['version'])
                            ->inc('sales', $v['amount'])
                            ->dec('stock', $v['amount'])
                            ->inc('version')
                            ->update();
                        if ($update > 0) {
                            if (!empty($v['spec_key'])) {
                                Db::name('goods_price')
                                    ->where(['goods_id' => $v['goods_id'], 'spec_key' => $v['spec_key']])
                                    ->inc('sales', $v['amount'])
                                    ->dec('stock', $v['amount'])
                                    ->update();
                            }
                            //如果是购物车提交就删除已购买商品
                            if ($buyType == 1) {
                                Db::name('cart')->where(['user_id' => $userId, 'goods_id' => $v['goods_id'], 'spec_key' => $v['spec_key']])->delete();
                            }
                        } else {
                            //并发回滚事务重新提交
                            Db::rollback();
                            $this->saveData($param);
                        }
                    }
                    //如果使用了优惠券就标注已使用
                    if ($couponId > 0) {
                        Db::name('coupon_user')
                            ->where('id', $couponId)
                            ->update([
                                'is_use' => 1,
                                'use_date' => time(),
                                'order_sn' => $orderSn
                            ]);
                    }
                    //如果是积分订单就扣积分
                    if($orderType == 'integral') {
                        $res1 = self::saveUserAccount($userId,$data['exchange_integral'] * -1,0,'订单:'.$orderSn.'兑换');
                        if($res1['code'] != 0) {
                            // 回滚事务
                            Db::rollback();
                            $res['code'] = 500;
                            $res['msg'] = $res1['msg'];
                        } else {
                            // 记录兑换日志
                            $goods = getGoodsInfo($goodsId, $specKey);
                            Event::trigger('saveIntegralLog',[
                                'order_id' => $orderId,
                                'user_id' => $userId,
                                'goods_id' => $goodsId,
                                'spec_key_name' => $goods['spec_key_name'],
                                'amount' => $amount
                            ]);
                        }
                    }
                    //记录下单日志
                    Event::trigger('OrderLog',[
                        'type' => 1,
                        'order_id' => $orderId,
                        'user_id' => $userId,
                        'info' => '订单：' . $orderSn . '，下单成功'
                    ]);
                    //无需支付的订单自动发货
                    if($orderState == 3) {
                        //记录订单发货日志
                        Event::trigger('OrderLog',[
                            'type' => 2,
                            'order_id' => $orderId,
                            'user_id' => $userId,
                            'info' => '订单：' . $orderSn . '，发货成功'
                        ]);
                        //发消息
                        Event::trigger('SendMessage',[
                            'sn' => $orderSn,
                            'type' => 'order',
                            'state' => 'send'
                        ]);
                    }
                    //提交事务
                    Db::commit();
                    $res['sn'] = $orderSn;
                    $res['is_pay'] = $orderState == 1 ? 0 : 1;
                } else {
                    // 回滚事务
                    Db::rollback();
                    $res['code'] = 500;
                    $res['msg'] = "商品不存在或库存不足";
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                $res['code'] = 500;
                $res['msg'] = $e->getMessage();
            }
            return $res;
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 取消订单
     * @param array $param
     * @return array
     */
    public function cancelData(array $param = []) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $ids = explode(",", $param['id']);
            foreach ($ids as $key => $value) {
                $order = OrderModel::where('user_id',$param['user_id'])
                    ->where('id',$value)
                    ->where('order_state',1)
                    ->find();
                if(!empty($order)) {
                    //取消订单
                    $res1 = Event::trigger('CancelOrder',[
                        'type' => 1,
                        'order_id' => $order['id'],
                        'user_id' => $order['user_id']
                    ]);
                    if($res1[0]['code'] > 0) {
                        return $res1[0];
                    }
                }
            }
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
            $ids = OrderModel::where('user_id',$param['user_id'])
                ->where('id','in',$ids)
                ->where('order_state',-1)
                ->column('id');
            OrderModel::where('id','in',$ids)->delete();
            Db::name('order_goods')->where('order_id','in',$ids)->delete();
            //删除退换货
            Db::name('order_refund')->where('user_id',$param['user_id'])->where('order_id','in',$ids)->delete();
            //删除对应的订单记录
            Db::name('order_log')->where('user_id',$param['user_id'])->where('order_id','in',$ids)->delete();
            //删除兑换日志
            Event::trigger('delIntegralLog',[
                'order_ids' => $ids,
                'user_id' => $param['user_id']
            ]);
            return $res;
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 确认收货
     * @param array $param
     * @return array
     */
    public function receiptData(array $param = []) : array
    {
        $res = Event::trigger('ReceiptOrder',[
            'm_id' => $param['id'],
            'opt_type' => 1
        ]);
        return $res[0];
    }

    /**
     * 获取快递信息
     * @param array $param
     * @return array
     */
    public function getExpress(array $param = []) : array
    {
        try {
            $tel = substr($param['tel'], -4);
            $sn = $param['express_sn'];
            $res = Cache::get('express_' . $sn,'');
            if(empty($res)) {
                $client = new Client(config('express.appCode'));
                $res = $client->expressQuery()
                    ->withCom('auto')
                    ->withNu($sn)
                    ->withPhone($tel)
                    ->request();
                if($res['code'] == 0 && $res['data']['status'] > 3) {
                    Cache::set('express_' . $sn,$res);
                }
            }
            return $res;
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }
}