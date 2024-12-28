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
        // 启动事务
        Db::startTrans();
        try {
            $order = Db::name('order')->where('id',$param['order_id'])->find();
            if(!empty($order)) {
                //还原库存销量
                $orderGoods = Db::name('order_goods')
                    ->where('order_id',$order['id'])
                    ->select();
                foreach ($orderGoods as $v) {
                    $goods = Db::name('goods')->where('id', $v['goods_id'])->find();
                    $update = Db::name('goods')
                        ->where('id', $goods['id'])
                        ->where('version', $goods['version'])
                        ->dec('sales', $v['amount'])
                        ->inc('stock', $v['amount'])
                        ->inc('version')
                        ->update();
                    if ($update > 0) {
                        //更新规格销量库存
                        Db::name('goods_price')
                            ->where(['goods_id' => $v['goods_id'], 'spec_key' => $v['spec_key']])
                            ->dec('sales', $v['amount'])
                            ->inc('stock', $v['amount'])
                            ->update();
                    } else {
                        //并发回滚事务重新提交
                        Db::rollback();
                        $this->handle($param);
                    }
                }
                //更改订单状态
                Db::name('order')->where('id',$order['id'])->update(['order_state' => -1]);
                //如果使用了积分就退回
                if($order['exchange_integral'] > 0) {
                    $res = BaseLogic::saveUserAccount($order['user_id'],$order['exchange_integral'],0,'订单' . $order['order_sn'] . '取消退回');
                    if($res['code'] > 0) {
                        //事务回滚
                        Db::rollback();
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
                //记录订单取消日志
                Event::trigger('OrderLog',[
                    'type' => $param['type'],
                    'order_id' => $param['order_id'],
                    'user_id' => $param['user_id'],
                    'info' => '订单：' . $order['order_sn'] . '，取消成功'
                ]);
                //提交事务
                Db::commit();
                return success();
            } else {
                return fail('订单不存在');
            }
        } catch (\Exception $e) {
            //事务回滚
            Db::rollback();
            return fail($e->getMessage());
        }
    }

}