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

namespace app\task\controller;

use app\common\logic\index\Order as OrderLogic;
use think\facade\Event;
use think\facade\Db;

class Index
{
    /**
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index() : string
    {
        $time = time();
        $closeTime = config('shop.close_time') * 60;
        $receiveTime = config('shop.send_time') * 24 * 3600;
        $commentTime = config('shop.finish_time') * 24 * 3600;
        $logic = new OrderLogic();
        //未付款自动取消
        $param = [];
        $list = Db::name('order')
            ->where('order_state',1)
            ->where('add_date','<',$time - $closeTime)
            ->select()
            ->toArray();
        foreach ($list as $value) {
            $param['user_id'] = $value['user_id'];
            $param['id'] = $value['id'];
            $logic->cancelData($param);
        }
        //自动收货
        $list = Db::name('order')
            ->where('order_state',3)
            ->where('express_date','<',$time - $receiveTime)
            ->select()
            ->toArray();
        foreach ($list as $value) {
            Event::trigger('ReceiptOrder',[
                'm_id' => $value['id'],
                'opt_type' => 1,
                'opt_user_id' => $value['user_id']
            ]);
        }
        //自动好评
        $orderIds = Db::name('order')
            ->where('order_state',4)
            ->where('receive_date','<',$time - $commentTime)
            ->column('id');
        $list = Db::name('order_goods')
            ->where('order_id','in',$orderIds)
            ->where('is_comment',0)
            ->select()
            ->toArray();
        foreach ($list as $value) {
            $userId = Db::name('order')->where('id',$value['order_id'])->value('user_id');
            $data = [
                'order_id' => $value['order_id'],
                'goods_id' => $value['goods_id'],
                'spec_key_name' => $value['spec_key_name'],
                'user_id' => $userId,
                'user_name' => getUserLevel($userId)['uid'],
                'goods_rate' => 5,
                'express_rate' => 5,
                'service_rate' => 5,
                'info' => '整个购物体验非常愉快，还会再来！',
                'add_date' => time()
            ];
            $res = Db::name('goods_comment')->insert($data);
            if($res > 0) {
                Db::name('order_goods')
                    ->where('id',$value['id'])
                    ->update(['is_comment' => 1]);
            }
        }
        //结束
        $run_time = time() - $time;
        return '任务完成，用时：' . $run_time . '秒！';
    }
}