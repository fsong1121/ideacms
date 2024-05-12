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
namespace app\api\controller\v1\index;

use app\common\service\Pay as PayService;
use app\common\service\Wechat as WechatService;
use think\response\Json;
use think\facade\Request;
use think\facade\Event;
use think\facade\Db;

class Pay extends Base
{

    /**
     * 获取列表
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList() : Json
    {
        $res = $this->setParam(Request::param());
        if($res['code'] == 0) {
            $param = $res['data'];
            if(!empty($param['user_id'])) {
                $data = [];
                $pay = config('pay');
                if($pay['wechat_pay']['open'] == 1) {
                    array_push($data,[
                        'type' => 1,
                        'title' => '微信支付',
                        'info' => '财付通支付科技有限公司'
                    ]);
                }
                if($pay['ali_pay']['open'] == 1) {
                    array_push($data,[
                        'type' => 2,
                        'title' => '支付宝支付',
                        'info' => '支付宝（中国）网络技术有限公司'
                    ]);
                }
                if(empty($param['type'])) {
                    //订单支付才有
                    if ($pay['balance_pay'] == 1) {
                        array_push($data, [
                            'type' => 4,
                            'title' => '余额支付',
                            'info' => '使用会员余额进行支付'
                        ]);
                    }
                    if ($pay['cod_pay'] == 1) {
                        array_push($data, [
                            'type' => 5,
                            'title' => '货到付款',
                            'info' => '货物送达时付款给送货员'
                        ]);
                    }
                }
                $res = success($data);
            } else {
                $res = fail('请先登录',401);
            }
        }
        return json($res);
    }

    /**
     * 余额支付
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function balancePay() : Json
    {
        if (Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    $type = $param['type'] ?? '';
                    $sn = $param['sn'] ?? '';
                    if(empty($type)) {
                        $order = Db::name('order')
                            ->where('order_sn', $sn)
                            ->where('user_id',$param['user_id'])
                            ->where('order_state', 1)
                            ->find();
                        if (!empty($order)) {
                            $params['type'] = $type;
                            $params['order_sn'] = $sn;
                            $params['user_id'] = $param['user_id'];
                            $params['pay_total'] = 0;
                            $params['pay_sn'] = '';
                            $params['pay_order_sn'] = '';
                            $params['pay_time'] = time();
                            $params['pay_type'] = 4;
                            $params['pay_gateway'] = '';
                            $res1 = Event::trigger('PaySuccess',$params);
                            $res =  $res1[0];
                        } else {
                            $res = fail('订单不存在或已支付');
                        }
                    } else {
                        $res = fail('不支持余额支付');
                    }
                } else {
                    $res = fail('请先登录',401);
                }
            }
            return json($res);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 货到付款
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function codPay() : Json
    {
        if (Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    $type = $param['type'] ?? '';
                    $sn = $param['sn'] ?? '';
                    if(empty($type)) {
                        $order = Db::name('order')
                            ->where('order_sn', $sn)
                            ->where('user_id',$param['user_id'])
                            ->where('order_state', 1)
                            ->find();
                        if (!empty($order)) {
                            $params['type'] = $type;
                            $params['order_sn'] = $sn;
                            $params['user_id'] = $param['user_id'];
                            $params['pay_total'] = 0;
                            $params['pay_sn'] = '';
                            $params['pay_order_sn'] = '';
                            $params['pay_time'] = '';
                            $params['pay_type'] = 5;
                            $params['pay_gateway'] = '';
                            $res1 = Event::trigger('PaySuccess',$params);
                            $res =  $res1[0];
                        } else {
                            $res = fail('订单不存在或已支付');
                        }
                    } else {
                        $res = fail('不支持货到付款');
                    }
                } else {
                    $res = fail('请先登录',401);
                }
            }
            return json($res);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 微信支付
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response|Json|void|\Yansongda\Supports\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function wechatPay()
    {
        if (Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    $type = $param['type'] ?? '';
                    $sn = $param['sn'] ?? '';
                    $total = $param['total'] ?? 0;
                    $openid = $param['openid'] ?? '';
                    $gateway = $param['gateway'] ?? 'mp';
                    $mpCode = $param['code'] ?? '';
                    if(empty($type)) {
                        $order = Db::name('order')
                            ->where('order_sn', $sn)
                            ->where('user_id',$param['user_id'])
                            ->where('order_state', 1)
                            ->find();
                        if (!empty($order)) {
                            $total = $order['pay_price'];
                        } else {
                            return json(fail('订单不存在或已支付'));
                        }
                    }
                    //会员充值
                    if($type == 'recharge') {
                        $sn = makeOrderSn('R') . '_' . $param['user_id'];
                    }
                    //vip购买
                    if($type == 'vip') {
                        $sn = makeOrderSn('V') . '_' . $param['user_id'];
                    }
                    //如果是公众号，先获取openid
                    if($gateway == 'mp') {
                        $weChat = new WechatService();
                        $mpRes = $weChat->getMpOpenId($mpCode);
                        if($mpRes['code'] == 0) {
                            $openid = $mpRes['data']['openid'];
                        } else {
                            return json(fail($mpRes['msg']));
                        }
                    }
                    $service = new PayService();
                    $data = $service->wxPay($sn,$openid,$total,$gateway,$type);
                    $res = ['code' => 0,'data' => $data,'msg' => 'success'];
                } else {
                    $res = fail('请先登录',401);
                }
            }
            return json($res);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 支付宝支付
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function aliPay() : Json
    {
        if (Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    $type = $param['type'] ?? '';
                    $sn = $param['sn'] ?? '';
                    $total = $param['total'] ?? 0;
                    $gateway = $param['gateway'] ?? 'wap';
                    if(empty($type)) {
                        $order = Db::name('order')
                            ->where('order_sn', $sn)
                            ->where('user_id',$param['user_id'])
                            ->where('order_state', 1)
                            ->find();
                        if (!empty($order)) {
                            $total = $order['pay_price'];
                        } else {
                            return json(fail('订单不存在或已支付'));
                        }
                    }
                    //会员充值
                    if($type == 'recharge') {
                        $sn = makeOrderSn('R') . '_' . $param['user_id'];
                    }
                    //vip购买
                    if($type == 'vip') {
                        $sn = makeOrderSn('V') . '_' . $param['user_id'];
                    }
                    $service = new PayService();
                    $data = $service->aliPay($sn,$total,$gateway,$type);
                    $res = ['code' => 0,'data' => $data,'msg' => 'success'];
                } else {
                    $res = fail('请先登录',401);
                }
            }
            return json($res);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }


}