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
namespace app\common\event;

use app\common\logic\BaseLogic;
use think\facade\Event;
use think\facade\Db;

/**
 * 支付成功
 */
class PaySuccess
{
    /**
     * 行为扩展的执行入口必须是run
     * @param array $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function handle(array $param = []) : array
    {
        if($param['type'] == 'recharge') {
            //会员充值
            $res = BaseLogic::saveUserAccount($param['user_id'], $param['pay_total'], 1, '会员余额充值成功');
            if ($res['code'] == 0) {
                //记录资金流水
                if($param['pay_type'] < 4) {
                    Event::trigger('FinanceDetail',[
                        'related_sn' => $res['data']['sn'],
                        'user_id' => $param['user_id'],
                        'fee' => $param['pay_total'],
                        'info' => '会员ID：' . $param['user_id'] . '，充值成功',
                        'pay_type' => $param['pay_type'],
                        'pay_sn' => $param['pay_sn'],
                        'pay_gateway' => $param['pay_gateway'],
                        'type' => 3
                    ]);
                }
            }
            return $res;
        } elseif ($param['type'] == 'vip') {
            //购买vip
            return success();
        } else {
            //订单支付
            $order = Db::name('order')
                ->where('order_sn',$param['order_sn'])
                ->where('order_state',1)
                ->find();
            if(!empty($order)) {
                $pickupSn = $order['send_type'] > 1 ? makeRandStr(6) : '';
                //余额支付，减用户余额
                if ($param['pay_type'] == 4) {
                    $res = BaseLogic::saveUserAccount($order['user_id'], -1 * $order['pay_price'], 1, '订单支付：' . $order['order_sn']);
                    if ($res['code'] != 0) {
                        return $res;
                    }
                }
                $data = [
                    'pay_type' => $param['pay_type'],
                    'pay_sn' => $param['pay_sn'],
                    'pay_order_sn' => $param['pay_order_sn'],
                    'pay_gateway' => $param['pay_gateway'],
                    'pay_date' => $param['pay_time'],
                    'pickup_sn' => $pickupSn
                ];
                $orderState = 2;
                //非普通商品支付后直接发货
                $orderGoods = Db::name('order_goods')
                    ->where('order_id',$order['id'])
                    ->select()
                    ->toArray();
                if(count($orderGoods) == 1 && getGoodsInfo($orderGoods[0]['goods_id'],'','type')['type'] > 0) {
                    $orderState = 3;
                    $data['express_type'] = 2;
                    $data['express_date'] = time();
                }
                $data['order_state'] = $orderState;
                //更新订单
                Db::name('order')->where('id', $order['id'])->update($data);
                //记录订单支付成功日志
                Event::trigger('OrderLog',[
                    'type' => 1,
                    'order_id' => $order['id'],
                    'user_id' => $order['user_id'],
                    'info' => '订单：' . $order['order_sn'] . '，支付成功'
                ]);
                //记录资金流水
                if($param['pay_type'] < 4) {
                    Event::trigger('FinanceDetail',[
                        'related_sn' => $order['order_sn'],
                        'user_id' => $order['user_id'],
                        'fee' => $order['pay_price'],
                        'info' => '订单：' . $order['order_sn'] . '，支付成功',
                        'pay_type' => $param['pay_type'],
                        'pay_sn' => $param['pay_sn'],
                        'pay_gateway' => $param['pay_gateway'],
                        'type' => 1
                    ]);
                }
                //发消息
                Event::trigger('SendMessage',[
                    'sn' => $order['order_sn'],
                    'type' => 'order',
                    'state' => 'pay'
                ]);
                //自动发货订单
                if($orderState == 3) {
                    //记录订单发货日志
                    Event::trigger('OrderLog',[
                        'type' => 2,
                        'order_id' => $order['id'],
                        'user_id' => $order['user_id'],
                        'info' => '订单：' . $order['order_sn'] . '，发货成功'
                    ]);
                    //发消息
                    Event::trigger('SendMessage',[
                        'sn' => $order['order_sn'],
                        'type' => 'order',
                        'state' => 'send'
                    ]);
                }
                return success();
            } else {
                return fail('订单不存在或已支付');
            }
        }
    }

}