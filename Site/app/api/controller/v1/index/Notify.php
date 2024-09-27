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
use think\facade\Event;
use think\facade\Request;
use think\facade\Log;

class Notify
{
    /**
     * 微信支付回调
     * @throws \Yansongda\Pay\Exceptions\InvalidArgumentException
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function weChatPay()
    {
        $params = [];
        $pay = new PayService();
        $data = $pay->wxVerify();
        if($data->return_code == 'SUCCESS') {
            $type = '';
            if (str_contains($data->out_trade_no, 'R')) {
                $type = 'recharge';
            }
            if (str_contains($data->out_trade_no, 'V')) {
                $type = 'vip';
            }
            if (str_contains($data->out_trade_no, 'TI')) {
                $type = 'ticketing';
            }
            if (str_contains($data->out_trade_no, 'TS')) {
                $type = 'tickets';
            }
            $out_trade_no = explode('_',$data->out_trade_no);
            $params['type'] = $type;
            $params['order_sn'] = $out_trade_no[0];
            $params['user_id'] = $out_trade_no[1];
            $params['pay_total'] = $data->total_fee / 100;
            $params['pay_sn'] = $data->transaction_id;
            $params['pay_order_sn'] = $data->out_trade_no;
            $params['pay_time'] = strtotime($data->time_end);
            $params['pay_type'] = 1;
            $params['pay_gateway'] = $data->attach;
            Event::trigger('PaySuccess',$params);
        }
    }

    /**
     * 支付宝支付回调
     * @throws \Yansongda\Pay\Exceptions\InvalidConfigException
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function alipay()
    {
        $params = [];
        $pay = new PayService();
        $data = $pay->aliVerify();
        if($data->trade_status == 'TRADE_SUCCESS') {
            $type = '';
            if (str_contains($data->out_trade_no, 'R')) {
                $type = 'recharge';
            }
            if (str_contains($data->out_trade_no, 'V')) {
                $type = 'vip';
            }
            if (str_contains($data->out_trade_no, 'TI')) {
                $type = 'ticketing';
            }
            if (str_contains($data->out_trade_no, 'TS')) {
                $type = 'tickets';
            }
            $out_trade_no = explode('_',$data->out_trade_no);
            $params['type'] = $type;
            $params['order_sn'] = $out_trade_no[0];
            $params['user_id'] = $out_trade_no[1];
            $params['pay_total'] = $data->total_amount;
            $params['pay_sn'] = $data->trade_no;
            $params['pay_order_sn'] = $data->out_trade_no;
            $params['pay_time'] = strtotime($data->gmt_payment);
            $params['pay_type'] = 2;
            $params['pay_gateway'] = '';
            Event::trigger('PaySuccess',$params);
        }
    }

    /**
     * 支付宝回调跳转
     */
    public function alipayReturn()
    {
        $pay = new PayService();
        $data = $pay->aliReturn();
        $outTradeNo = explode('_',$data->out_trade_no);
        if(strlen($outTradeNo[0]) == 16) {
            //商品订单
            if(in_array('wap',$outTradeNo)) {
                header('Location:' . config('site.url') . '/h5/pages/order/list?state=2');
            }
            if(in_array('web',$outTradeNo)) {
                header('Location:' . config('site.url') . '/index/order/list.html?state=2');
            }
        } else {
            if(str_contains($data->out_trade_no, 'TI')) {
                //购券
                if(in_array('wap',$outTradeNo)) {
                    header('Location:' . config('site.url') . '/h5/pages/addons/ticketing/order');
                }
                if(in_array('web',$outTradeNo)) {
                    header('Location:' . config('site.url') . '/addons/ticketing/index.order/index.html');
                }
            } elseif (str_contains($data->out_trade_no, 'TS')) {
                //票务
                if(in_array('wap',$outTradeNo)) {
                    header('Location:' . config('site.url') . '/h5/pages/addons/tickets/order');
                }
                if(in_array('web',$outTradeNo)) {
                    header('Location:' . config('site.url') . '/addons/tickets/index.order/index.html');
                }
            } else {
                //充值、VIP等
                if(in_array('wap',$outTradeNo)) {
                    header('Location:' . config('site.url') . '/h5/pages/user/index');
                }
                if(in_array('web',$outTradeNo)) {
                    header('Location:' . config('site.url') . '/index/user/index.html');
                }
            }
        }
    }
}