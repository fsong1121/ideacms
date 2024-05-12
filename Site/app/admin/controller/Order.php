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
namespace app\admin\controller;

use app\common\logic\admin\Order as OrderLogic;
use app\common\validate\admin\Order as OrderValidate;
use think\facade\Db;
use think\facade\Event;
use think\facade\Request;
use think\facade\Session;
use think\response\Json;

class Order extends Base
{
    /**
     * 列表
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/order');
    }

    /**
     * 编辑
     * @param int $id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(int $id = 0) : string
    {
        $logic = new OrderLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/order_edit');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 获取列表
     */
    public function getList() : Json
    {
        $param = Request::param();
        $perPage = Request::has('limit', 'get') ? $param['limit'] : $this->perPage;
        $keys = trimStr(Request::has('keys','get') ? $param['keys'] : '');
        $k2 = Request::has('k2','get') ? $param['k2'] : '';
        $k3 = Request::has('k3','get') ? $param['k3'] : '';
        $k4 = Request::has('k4','get') ? $param['k4'] : '';
        $k5 = Request::has('k5','get') ? $param['k5'] : '';
        $param['limit'] = $perPage;
        $param['keys'] = $keys;
        $param['k2'] = $k2;
        $param['k3'] = $k3;
        $param['k4'] = $k4;
        $param['k5'] = $k5;
        Session::set('order_keys',$keys);
        Session::set('order_k2',$k2);
        Session::set('order_k3',$k3);
        Session::set('order_k4',$k4);
        Session::set('order_k5',$k5);
        $logic = new OrderLogic();
        return json($logic->readList($param));
    }

    /**
     * 配货单
     * @param int $id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function printOrder(int $id = 0) : string
    {
        $logic = new OrderLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/order_print');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 调整价格
     * @param $id
     * @return string|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editPrice($id)
    {
        $logic = new OrderLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/frame_edit_price');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 保存价格修改
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function savePrice() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $param = Request::param();
            $validate = new OrderValidate();
            $result = $validate->check($param);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $logic = new OrderLogic();
                $data = $logic->savePrice($param);
                return json($data);
            }
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 修改备注
     * @param $id
     * @return string|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editInfo($id)
    {
        $logic = new OrderLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/frame_edit_info');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 保存备注修改
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveInfo() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $param = Request::param();
            $logic = new OrderLogic();
            $data = $logic->saveInfo($param);
            return json($data);
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 保存收款
     * @return Json|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function savePay()
    {
        if (Request::isPost() && Request::isAjax()){
            $order = Db::name('order')
                ->where('id',Request::post('m_id'))
                ->where('order_state','>',2)
                ->where('pay_type',5)
                ->find();
            if(!empty($order) && empty($order['pay_date'])) {
                Db::name('order')->where('id',$order['id'])->update(['pay_date' => time()]);
                //记录资金流水
                Event::trigger('FinanceDetail',[
                    'related_sn' => $order['order_sn'],
                    'user_id' => $order['user_id'],
                    'fee' => $order['pay_price'],
                    'info' => '订单：' . $order['order_sn'] . '，货到付款支付成功',
                    'pay_type' => 5,
                    'pay_sn' => '',
                    'type' => 1
                ]);
                return json(success());
            } else {
                return json(fail('订单不存在或已收款!'));
            }
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 订单发货
     * @param $id
     * @return string|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function sendGoods($id)
    {
        $logic = new OrderLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            $this->assign('expressList',config('express.list'));
            return $this->fetch('/frame_send_goods');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 保存发货
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveSend() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $param = Request::param();
            $param['user_id'] = $this->uid;
            if($param['m_express_type'] == 1) {
                if(empty($param['m_express_sn'])) {
                    return json(fail('快递单号为空'));
                }
                $param['m_express_info'] = '';
            } else {
                $param['m_express_title'] = '';
                $param['m_express_sn'] = '';
            }
            $logic = new OrderLogic();
            return json($logic->saveSend($param));
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 订单收货
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function receipt() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $order = Db::name('order')
                ->where('id',Request::post('m_id'))
                ->where('order_state',3)
                ->find();
            if(!empty($order)) {
                //执行收货
                $res = Event::trigger('ReceiptOrder',[
                    'm_id' => $order['id'],
                    'opt_type' => 2,
                    'opt_user_id' => $this->uid
                ]);
                return json($res[0]);
            } else {
                return json(fail('订单不存在!'));
            }
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 取消订单
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function cancel() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $order = Db::name('order')
                ->where('id',Request::post('m_id'))
                ->where('order_state',1)
                ->find();
            if(!empty($order)) {
                //取消订单
                $res = Event::trigger('CancelOrder',[
                    'type' => 2,
                    'order_id' => $order['id'],
                    'user_id' => $this->uid
                ]);
                return json($res[0]);
            } else {
                return json(fail('订单不存在!'));
            }
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 删除订单
     * @return Json
     */
    public function delete() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $m_id = Request::post('m_id');
            $orderLogic = new OrderLogic();
            return json($orderLogic->delData($m_id));
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 导出数据
     * @return Json
     */
    public function exportData() : Json
    {
        $param = Request::param();
        $param['limit'] = $param['limit'] ?? $this->perPage;
        $param['keys'] = Session::get('order_keys');
        $param['k2'] = Session::get('order_k2');
        $param['k3'] = Session::get('order_k3');
        $param['k4'] = Session::get('order_k4');
        $param['k5'] = Session::get('order_k5');
        $logic = new OrderLogic();
        $res = $logic->readList($param);
        $dataList = [];
        foreach ($res['data'] as $key => $value) {
            $orderGoods = '';
            foreach ($value['goods'] as $k => $v) {
                if($k > 0) {
                    $orderGoods = $orderGoods . '；' . $v['title'];
                } else {
                    $orderGoods = $orderGoods . $v['title'];
                }
                if(!empty($v['spec_key_name'])) {
                    $orderGoods = $orderGoods . '，' . $v['spec_key_name'];
                }
                $orderGoods = $orderGoods . '，×' . $v['amount'];
            }
            $dataList[$key]['ID'] = $value['id'];
            $dataList[$key]['订单号'] = $value['order_sn'];
            $dataList[$key]['订单商品'] = $orderGoods;
            $dataList[$key]['会员'] = $value['uid'];
            $dataList[$key]['收货方式'] = $value['name'] . ',' . $value['tel'] . ',' . $value['address'];
            $dataList[$key]['订单金额'] = $value['price'];
            $dataList[$key]['实付金额'] = $value['pay_price'];
            $dataList[$key]['优惠券抵扣'] = $value['coupon_price'];
            $dataList[$key]['会员折扣'] = $value['rebate_price'];
            $dataList[$key]['满减优惠'] = $value['discount_price'];
            $dataList[$key]['积分抵扣'] = $value['exchange_price'];
            $dataList[$key]['订单调整'] = $value['trim_price'];
            $dataList[$key]['退款金额'] = $value['refund_price'];
            $dataList[$key]['运费'] = $value['express_price'];
            $dataList[$key]['使用积分'] = $value['exchange_integral'];
            $dataList[$key]['支付方式'] = $value['pay_type'];
            $dataList[$key]['支付时间'] = $value['pay_date'];
            $dataList[$key]['订单来源'] = $value['terminal'];
            $dataList[$key]['快递类型'] = $value['send_type'];
            $dataList[$key]['快递信息'] = empty($value['express_sn']) ? '' : $value['express_title'] . ':' . $value['express_sn'];
            $dataList[$key]['提货店铺'] = $value['store_id'];
            $dataList[$key]['提货号'] = $value['pickup_sn'];
            $dataList[$key]['发货时间'] = $value['order_state_no'] > 2 ? $value['express_date'] : '';
            $dataList[$key]['订单类型'] = $value['order_type'] == '' ? '普通订单' : $value['order_type'];
            $dataList[$key]['订单状态'] = $value['order_state'];
            $dataList[$key]['下单时间'] = $value['add_date'];
        }
        $res['data'] = $dataList;
        return json($res);
    }

}