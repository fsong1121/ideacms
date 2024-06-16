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

use app\common\model\OrderRefund as OrderRefundModel;
use app\common\model\Order as OrderModel;
use app\common\service\Pay as PayService;
use think\facade\Event;
use think\facade\Db;

class Refund extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        $orderRefund = OrderRefundModel::find($id);
        if(!empty($orderRefund)) {
            $orderRefund['order'] = OrderModel::where('id',$orderRefund['order_id'])->find();
            if(!empty($orderRefund['order'])) {
                $goodsList = Db::name('order_goods')->where('order_id',$orderRefund['order_id'])
                    ->field('id,goods_id,amount,price,spec_key_name,state')
                    ->select()
                    ->toArray();
                foreach ($goodsList as $k => $v) {
                    $goods = getGoodsInfo($v['goods_id'],'','title,pic');
                    $goodsList[$k]['goods_pic'] = getPic($goods['pic']);
                    $goodsList[$k]['goods_title'] = $goods['title'];
                }
                $orderRefund['goods_list'] = $goodsList;
            }
            $orderRefund['pic'] = empty($orderRefund['pic']) ? [] : explode(',',$orderRefund['pic']);
        }
        return $orderRefund;
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = OrderRefundModel::where('id', '>', '0');
            if($param['k2']!='') {
                $list = $list->where('state',$param['k2']);
            }
            if($param['k3'] != '' && $param['k4'] != ''){
                $list = $list->where('add_date','>=',strtotime($param['k3']))->where('add_date','<=',strtotime($param['k4']));
            }
            $list = $list->order(['id'=>'desc'])
                ->paginate($param['limit'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key]['order_sn'] = OrderModel::where('id',$value['order_id'])->value('order_sn');
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
     * 保存驳回
     * @param $param
     * @return array
     */
    public function saveRefuse($param) : array
    {
        try {
            $ids = explode(',',$param['m_id']);
            $info = $param['m_info'];
            foreach ($ids as $value) {
                $orderRefund = Db::name('order_refund')
                    ->where('state', 0)
                    ->where('id', $value)
                    ->find();
                if(!empty($orderRefund)) {
                    Db::name('order_refund')
                        ->where('id', $orderRefund['id'])
                        ->update([
                            'state' => -1,
                            'reply_info' => $info,
                            'reply_date' => time()
                        ]);
                    //更新订单及订单商品状态
                    Db::name('order')
                        ->where('id', $orderRefund['order_id'])
                        ->update([
                            'refund_price' => 0,
                            'refund_state' => 0
                        ]);
                    Db::name('order_goods')
                        ->where('order_id', $orderRefund['order_id'])
                        ->update([
                            'state' => 0
                        ]);
                }
            }
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 同意退款
     * @param $param
     * @return array
     */
    public function saveAgree($param) : array
    {
        try {
            $ids = explode(',',$param['m_id']);
            foreach ($ids as $value) {
                $orderRefund = Db::name('order_refund')
                    ->where('id',$value)
                    ->where('state',0)
                    ->find();
                if(!empty($orderRefund)) {
                    $order = Db::name('order')->where('id',$orderRefund['order_id'])->find();
                    if(!empty($order)) {
                        if($order['pay_type'] == 1) {
                            //微信支付
                            $pay = new PayService();
                            $paySn = empty($order['pay_sn']) ? '' : $order['pay_sn'];
                            $payGateway = empty($order['pay_gateway']) ? '' : $order['pay_gateway'];
                            $res = $pay->wxRefund($paySn,$order['pay_price'] * 100,$orderRefund['price'] * 100,'订单'.$order['order_sn'].'退款',$payGateway);
                            if($res->return_code != 'SUCCESS') {
                                return fail($res->return_msg);
                            }
                        }
                        if($order['pay_type'] == 2) {
                            //支付宝支付退款
                            $pay = new PayService();
                            $paySn = empty($order['pay_sn']) ? '' : $order['pay_sn'];
                            $res = $pay->aliRefund($paySn,$orderRefund['price'],'订单'.$order['order_sn'].'退款');
                            if($res->code != '10000') {
                                return fail($res->msg);
                            }
                        }
                        if($order['pay_type'] == 3) {
                            //网银支付退款
                        }
                        if($order['pay_type'] == 4) {
                            //余额支付
                            $res = self::saveUserAccount($orderRefund['user_id'],$orderRefund['price'],1,'订单'.$order['order_sn'].'退款');
                            if($res['code'] > 0) {
                                return fail($res['msg']);
                            }
                        }
                        //记录资金流水
                        if($order['pay_type'] < 4 && $orderRefund['price'] > 0) {
                            Event::trigger('FinanceDetail',[
                                'related_sn' => $order['order_sn'],
                                'user_id' => $order['user_id'],
                                'fee' => $orderRefund['price'] * -1,
                                'info' => '订单：' . $order['order_sn'] . '，退款成功',
                                'pay_type' => $order['pay_type'],
                                'pay_sn' => '',
                                'pay_gateway' => '',
                                'type' => 2
                            ]);
                        }
                        //取消订单
                        $res = Event::trigger('CancelOrder',[
                            'type' => 1,
                            'order_id' => $order['id'],
                            'user_id' => $order['user_id']
                        ]);
                        if($res[0]['code'] > 0) {
                            return $res[0];
                        }
                        //更改退款状态
                        Db::name('order_refund')
                            ->where('id',$value)
                            ->update([
                                'state' => 1,
                                'reply_date' => time()
                            ]);
                        //更新订单及订单商品状态
                        Db::name('order')
                            ->where('id', $orderRefund['order_id'])
                            ->update([
                                'refund_state' => 2
                            ]);
                        Db::name('order_goods')
                            ->where('order_id', $orderRefund['order_id'])
                            ->update([
                                'state' => 2
                            ]);
                    }
                }
            }
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }
}