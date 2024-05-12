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

use app\common\model\Order as OrderModel;
use app\common\model\OrderGoods as OrderGoodsModel;
use app\common\model\OrderLog as OrderLogModel;
use think\facade\Event;
use think\facade\Db;

class Order extends Base
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
        $data = OrderModel::where('id',$id)->find();
        if(!empty($data)) {
            $data['order_type_title'] = config('order.order_type')[$data['order_type']];
            $data['goods'] = OrderGoodsModel::where('order_id',$data['id'])->select()->toArray();
            $data['logs'] = OrderLogModel::where('order_id',$data['id'])->select()->toArray();
        }
        return $data;
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = OrderModel::where('id', '>',0);
            //是否成功，比如拼团和助力订单
            if (isset($param['activity_state'])) {
                $list = $list->where('activity_state', $param['activity_state']);
            } else {
                $list = $list->where('activity_state', 1);
            }
            if($param['keys']!='') {
                $list = $list->where('order_sn|name|tel','like','%'.$param['keys'].'%');
            }
            if($param['k2']!='') {
                $list = $list->where('order_state',$param['k2']);
            }
            if($param['k3'] != '' && $param['k4'] != ''){
                $list = $list->where('add_date','>=',strtotime($param['k3']))->where('add_date','<=',strtotime($param['k4']));
            }
            if(!empty($param['k5'])){
                if($param['k5'] == 'normal') {
                    $list = $list->where('order_type', '');
                } else {
                    $list = $list->where('order_type', $param['k5']);
                }
            }
            if(isset($param['m_id']) && !empty($param['m_id'])) {
                $list = $list->where('id','in',$param['m_id']);
            }
            $list = $list->order(['id'=>'desc'])
                ->paginate($param['limit'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $orderGoods = OrderGoodsModel::where('order_id',$value['id'])->select()->toArray();
                foreach ($orderGoods as $k => $v) {
                    $goods = getGoodsInfo($v['goods_id'],'','title,pic');
                    $orderGoods[$k]['title'] = $goods['title'];
                    $orderGoods[$k]['pic'] = getPic($goods['pic']);
                }
                $list['data'][$key]['goods'] = $orderGoods;
                $list['data'][$key]['order_state_no'] = Db::name('order')
                    ->where('id',$value['id'])
                    ->value('order_state');
                $list['data'][$key]['uid'] = Db::name('user')
                    ->where('id',$value['user_id'])
                    ->value('uid');
                $list['data'][$key]['order_type_title'] = config('order.order_type')[$value['order_type']];
            }
            return [
                'code' => 0,
                'msg' => '',
                'count' => $list['total'],
                'data' => $list['data'],
                'param' => $param
            ];
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 删除
     * @param string $id
     * @return array
     */
    public function delData(string $id = '') : array
    {
        $ids = explode(",", $id);
        try {
            if(count($ids) == 1) {
                $order = OrderModel::where('id',$id)->where('order_state', -1)->find();
                if(empty($order)) {
                    return fail('当前状态不允许删除，请先取消订单！');
                }
            }
            $orderIds = OrderModel::where('id', 'in',$ids)
                ->where('order_state', -1)
                ->column('id');
            OrderModel::where('id', 'in',$orderIds)->delete();
            OrderGoodsModel::where('order_id', 'in',$orderIds)->delete();
            //删除对应的订单记录
            OrderLogModel::where('order_id', 'in',$orderIds)->delete();
            //删除退换货
            Db::name('order_refund')->where('order_id','in',$orderIds)->delete();
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 保存价格调整(待付款订单才可以调)
     * @param array $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function savePrice(array $param = []) : array
    {
        $data = OrderModel::where('id',$param['m_id'])->find();
        if(!empty($data)){
            $y_pay_price = $data['pay_price'];
            $y_express_price = $data['express_price'];
            $y_trim_price = $data['trim_price'];
            $m_express_price = $param['m_express_price'];
            $m_trim_price = $param['m_trim_price'];
            $change_price = $m_express_price - $y_express_price + $m_trim_price - $y_trim_price;
            $pay_price = $y_pay_price + $change_price;
            try {
                if($data->getData('order_state') == 1) {
                    OrderModel::update([
                        'pay_price'=>$pay_price,
                        'express_price'=>$m_express_price,
                        'trim_price'=>$m_trim_price
                    ],['id'=>$param['m_id']]);
                    //分摊到订单商品中
                    $orderGoods = OrderGoodsModel::where('order_id',$param['m_id'])->select();
                    foreach ($orderGoods as $value) {
                        $goods_trim_price = formatPrice($value['price'] * $value['amount']/$data['price'] * $m_trim_price);
                        OrderGoodsModel::update(['trim_price'=>$goods_trim_price],['id'=>$value['id']]);
                    }
                    return success();
                }
                else {
                    return fail('目前状态不可调价');
                }
            }
            catch (\Exception $e) {
                return fail($e->getMessage());
            }
        }
        else {
            return fail('订单不存在');
        }
    }

    /**
     * 保存订单备注
     * @param array $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveInfo(array $param = []) : array
    {
        $data = OrderModel::where('id',$param['m_id'])->find();
        if(!empty($data)){
            try {
                OrderModel::update([
                    'info' => $param['m_info']
                ],['id'=>$param['m_id']]);
                return success();
            }
            catch (\Exception $e) {
                return fail($e->getMessage());
            }
        }
        else {
            return fail('订单不存在');
        }
    }

    /**
     * 保存发货
     * @param array $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveSend(array $param = []) : array
    {
        $data = OrderModel::where('id',$param['m_id'])->find();
        if(!empty($data)){
            try {
                if((($data->getData('order_state') == 2 && $data['order_type'] == '') || ($data->getData('order_state') == 2 && $data['order_type'] != '' && $data['activity_state'] > 0)) || $data->getData('order_state') == 3) {
                    $data->save([
                        'express_type' => $param['m_express_type'],
                        'express_title' => $param['m_express_title'],
                        'express_sn' => $param['m_express_sn'],
                        'express_info' => $param['m_express_info'],
                        'express_date' => time(),
                        'order_state' => 3
                    ]);
                    //记录订单发货日志
                    Event::trigger('OrderLog',[
                        'type' => 2,
                        'order_id' => $data['id'],
                        'user_id' => $param['user_id'],
                        'info' => '订单：' . $data['order_sn'] . '，发货成功'
                    ]);
                    //发消息
                    Event::trigger('SendMessage',[
                        'sn' => $data['order_sn'],
                        'type' => 'order',
                        'state' => 'send'
                    ]);
                    return success();
                }
                else {
                    return fail('目前状态不可发货');
                }
            }
            catch (\Exception $e) {
                return fail($e->getMessage());
            }
        }
        else {
            return fail('订单不存在');
        }
    }

}