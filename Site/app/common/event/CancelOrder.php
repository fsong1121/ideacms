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
 * 取消订单
 */
class CancelOrder
{
    /**
     * 行为扩展的执行入口必须是run
     * @param array $param
     * @return array
     */
    public function handle(array $param = []) : array
    {
        try {
            $order = Db::name('order')->where('id',$param['order_id'])->find();
            if(!empty($order)) {
                //如果使用了积分就退回
                if($order['exchange_integral'] > 0) {
                    $res = BaseLogic::saveUserAccount($order['user_id'],$order['exchange_integral'],0,'订单' . $order['order_sn'] . '取消退回');
                    if($res['code'] > 0) {
                        return $res;
                    }
                }
                //如果有用优惠券就退回
                if($order['coupon_id'] > 0) {
                    Db::name('coupon_user')
                        ->where('id',$order['coupon_id'])
                        ->update([
                            'is_use' => 0,
                            'order_sn' => ''
                        ]);
                }
                //更改订单状态
                Db::name('order')->where('id',$order['order_id'])->update(['order_state' => -1]);
                //记录订单取消日志
                Event::trigger('OrderLog',[
                    'type' => $param['type'],
                    'order_id' => $param['order_id'],
                    'user_id' => $param['user_id'],
                    'info' => '订单：' . $order['order_sn'] . '，取消成功'
                ]);
                return success();
            } else {
                return fail('订单不存在');
            }
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

}