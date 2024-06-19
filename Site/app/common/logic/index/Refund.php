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
use app\common\model\OrderRefund as OrderRefundModel;
use think\facade\Event;
use think\facade\Db;

class Refund extends BaseLogic
{
    /**
     * 读取数据
     * @param array $param
     * @return array
     */
    public function readData(array $param = []) : array
    {
        try {
            $data = OrderRefundModel::where('user_id',$param['user_id'])
                ->where('id',$param['id'])
                ->find();
            if(!empty($data)) {
                $orderGoods = Db::name('order_goods')->where('order_id',$data['order_id'])
                    ->field('id,goods_id,amount,price,spec_key_name')
                    ->select()
                    ->toArray();
                foreach ($orderGoods as $k => $v) {
                    $goods = getGoodsInfo($v['goods_id'],'','title,pic');
                    $orderGoods[$k]['goods_pic'] = getPic($goods['pic']);
                    $orderGoods[$k]['goods_title'] = $goods['title'];
                }
                $data['goods_list'] = $orderGoods;
                $data['order_sn'] = Db::name('order')->where('id',$data['order_id'])->value('order_sn');
                $data['express_sn'] = empty($data['express_sn']) ? '' : $data['express_sn'];
            }
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => $data
            ];
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
            $list = OrderRefundModel::where('user_id',$param['user_id']);
            if($param['state'] == 1) {
                $list = $list->where('state',0);
            }
            if($param['state'] == 2) {
                $list = $list->where('state',1);
            }
            if($param['state'] == 3) {
                $list = $list->where('state',-1);
            }
            $list = $list->order(['id'=>'desc'])
                ->paginate($param['size'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $orderGoods = Db::name('order_goods')->where('order_id',$value['order_id'])
                    ->field('id,goods_id,amount,price,spec_key_name,exchange_integral')
                    ->select()
                    ->toArray();
                foreach ($orderGoods as $k => $v) {
                    $goods = getGoodsInfo($v['goods_id'],'','title,pic');
                    $orderGoods[$k]['goods_pic'] = getPic($goods['pic']);
                    $orderGoods[$k]['goods_title'] = $goods['title'];
                }
                $list['data'][$key]['goods_list'] = $orderGoods;
            }
            return [
                'code' => 0,
                'msg' => 'success',
                'count' => $list['total'],
                'data' => $list['data']
            ];
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
        // 启动事务
        Db::startTrans();
        try {
            $userId = $param['user_id'];
            $order = Db::name('order')
                ->where('id',$param['id'])
                ->where('user_id',$userId)
                ->where('order_state','>',1)
                ->find();
            if(!empty($order)) {
                if(config('shop.is_refund') == 0 || ($order['order_state'] > 2 && time() - $order['express_date'] > config('shop.refund_time') * 86400)) {
                    return fail('订单不可退款或已过退换期');
                }
                if($order['refund_state'] > 0) {
                    return fail('订单已在退款中，管理员正在审核！');
                }
                $price = $order['pay_price'];
                $expressPrice = 0;
                if($order['order_state'] > 2) {
                    //已发货，运费不退
                    $price = formatPrice($order['pay_price'] - $order['express_price']);
                    $expressPrice = $order['express_price'];
                }
                $state = $price > 0 || $order['exchange_integral'] > 0 ? 0 : 1;
                $replyDate = $price > 0 || $order['exchange_integral'] > 0 ? 0 : time();
                $data = [
                    'sn' => makeOrderSn('R'),
                    'user_id' => $userId,
                    'order_id' => $param['id'],
                    'type' => $param['type'],
                    'reason' => $param['reason'],
                    'pay_price' => $order['pay_price'],
                    'express_price' => $expressPrice,
                    'price' => $price,
                    'integral' => $order['exchange_integral'],
                    'info' => $param['info'],
                    'pic' => $param['pic'],
                    'state' => $state,
                    'add_date' => time(),
                    'reply_date' => $replyDate
                ];
                OrderRefundModel::create($data);
                //更新订单及订单商品状态
                Db::name('order')
                    ->where('id',$order['id'])
                    ->update([
                        'refund_price' => $price,
                        'refund_state' => 2
                    ]);
                Db::name('order_goods')
                    ->where('order_id',$order['id'])
                    ->update([
                        'state' => $state + 1
                    ]);
                //退款金额为0且没有用积分的直接成功并取消订单
                if($price == 0 && $order['exchange_integral'] == 0) {
                    //取消订单
                    $res = Event::trigger('CancelOrder',[
                        'type' => 1,
                        'order_id' => $order['id'],
                        'user_id' => $order['user_id']
                    ]);
                    if($res[0]['code'] > 0) {
                        // 回滚事务
                        Db::rollback();
                        return $res[0];
                    }
                }
                // 提交事务
                Db::commit();
                return success();
            } else {
                return fail('订单不存在');
            }
        }
        catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return fail($e->getMessage());
        }
    }

    /**
     * 取消
     * @param array $param
     * @return array
     */
    public function cancelData(array $param = []) : array
    {
        try {
            $ids = explode(",", $param['id']);
            $newIds = OrderRefundModel::where('user_id',$param['user_id'])
                ->where('id','in',$ids)
                ->where('state',0)
                ->column('id');
            $orderIds = OrderRefundModel::where('user_id',$param['user_id'])
                ->where('id','in',$ids)
                ->where('state',0)
                ->column('order_id');
            //更新退换货状态
            Db::name('order_refund')
                ->where('id','in',$newIds)
                ->update([
                    'state' => -2
                ]);
            //更新订单及订单商品状态
            Db::name('order')
                ->where('id','in',$orderIds)
                ->update([
                    'refund_price' => 0,
                    'refund_state' => 0
                ]);
            Db::name('order_goods')
                ->where('order_id','in',$orderIds)
                ->update([
                    'state' => 0
                ]);
            return success();
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
            $ids = explode(",", $param['id']);
            OrderRefundModel::where('user_id',$param['user_id'])
                ->where('id','in',$ids)
                ->where('state',-2)
                ->delete();
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 保存退货物流
     * @param array $param
     * @return array
     */
    public function saveExpressData(array $param = []) : array
    {
        try {
            $userId = $param['user_id'];
            $orderRefund = OrderRefundModel::where('id',$param['id'])
                ->where('user_id',$userId)
                ->find();
            if(!empty($orderRefund)) {
                $orderRefund->express_title = $param['express_title'];
                $orderRefund->express_sn = $param['express_sn'];
                $orderRefund->save();
                return success();
            } else {
                return fail('添加失败');
            }
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }
}