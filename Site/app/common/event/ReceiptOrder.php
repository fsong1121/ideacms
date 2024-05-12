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
use app\common\logic\admin\Coupon as CouponLogic;
use think\facade\Event;
use think\facade\Db;

/**
 * 确认收货
 */
class ReceiptOrder
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
        try {
            $ids = explode(",", $param['m_id']);
            foreach ($ids as $value) {
                $order = Db::name('order')
                    ->where('id',$value)
                    ->where('order_state',3)
                    ->find();
                if(!empty($order)) {
                    $optType = $param['opt_type']; //1为用户操作 2为商家后台操作
                    $userId = $optType == 1 ? $order['user_id'] : $param['opt_user_id'];
                    //更新订单状态
                    Db::name('order')
                        ->where('id', $order['id'])
                        ->update(['order_state' => 4,'receive_date' => time()]);
                    //送积分，优惠券(满减送)
                    if($order['discount_integral'] > 0) {
                        BaseLogic::saveUserAccount($order['user_id'],$order['discount_integral'],0,'订单'.$order['order_sn'].'满减赠送');
                    }
                    if(!empty($order['discount_coupon_ids'])) {
                        $coupon_ids = explode(",", $order['discount_coupon_ids']);
                        $logic = new CouponLogic();
                        foreach ($coupon_ids as $v) {
                            $logic->saveGive(['user_id' => $order['user_id'],'m_coupon_id' => $v],1);
                        }
                    }
                    //送积分，成长值(商品积分，成长值)
                    if($order['order_integral'] > 0) {
                        BaseLogic::saveUserAccount($order['user_id'],$order['order_integral'],0,'订单'.$order['order_sn'].'赠送');
                    }
                    if($order['order_growth'] > 0) {
                        BaseLogic::saveUserAccount($order['user_id'],$order['order_growth'],3,'订单'.$order['order_sn'].'赠送');
                        //如果成长值达到某级就自动升级会员等级
                        $userGrowth = Db::name('user')->where('id',$order['user_id'])->value('growth');
                        $userLevel = Db::name('user_level')
                            ->where('growth','<=',$userGrowth)
                            ->order('growth','desc')
                            ->find();
                        if(!empty($userLevel)) {
                            Db::name('user')->where('id',$order['user_id'])->update(['level_id' => $userLevel['id']]);
                        }
                    }
                    //记录订单日志
                    Event::trigger('OrderLog',[
                        'type' => $optType,
                        'order_id' => $order['id'],
                        'user_id' => $userId,
                        'info' => '订单：' . $order['order_sn'] . '，收货成功'
                    ]);
                    //订单分佣(开启分销情况下)
                    Event::trigger('distributionShare',[
                        'order_id' => $order['id']
                    ]);
                }
            }
            return success();
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

}