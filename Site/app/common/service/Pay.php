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

namespace app\common\service;

use Yansongda\Pay\Pay as YPay;
use think\facade\Config;

class Pay
{
    protected array $wxConfig = [];
    protected array $aliConfig = [];

    /**
     * 初始化
     */
    public function __construct()
    {
        //微信支付配置
        $this->wxConfig = [
            'appid' => '', // APP APPID
            'app_id' => Config::get('wechat.mp.appid'),   // 公众号 APPID
            'miniapp_id' => Config::get('wechat.miniapp.appid'),     // 小程序 APPID
            'mch_id' => Config::get('pay.wechat_pay.mchid'),         // 微信支付商户号
            'key' => Config::get('pay.wechat_pay.key'),              // 微信支付秘钥
            'notify_url' => Config::get('site.url') . Config::get('pay.wechat_pay.notify_url'),  // 回调地址
            'cert_client' => Config::get('pay.wechat_pay.apiclient_cert'), // optional，退款等情况时用到
            'cert_key' => Config::get('pay.wechat_pay.apiclient_key'),// optional，退款等情况时用到
            'log' => [ // optional
                'file' => './logs/wechat.log',
                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'single', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
            'http' => [ // optional
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
            ],
        ];
        //支付宝支付配置
        $this->aliConfig = [
            'app_id' => Config::get('pay.ali_pay.app_id'),
            'notify_url' => Config::get('site.url') . Config::get('pay.ali_pay.notify_url'),
            'return_url' => Config::get('site.url') . Config::get('pay.ali_pay.return_url'),
            'ali_public_key' => Config::get('pay.ali_pay.public_key'),
            'private_key' => Config::get('pay.ali_pay.private_key'),
            'log' => [ // optional
                'file' => './logs/alipay.log',
                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'single', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
            'http' => [ // optional
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
            ],
        ];
    }

    /**
     * 微信支付
     * @param string $order_sn
     * @param string $openid
     * @param float $total
     * @param string $gateway mp miniapp wap scan app
     * @param string $pay_type 空:订单 recharge:充值 vip:vip购买
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response|void|\Yansongda\Supports\Collection
     */
    public function wxPay(string $order_sn = '', string $openid = '', float $total = 0, string $gateway = 'mp', string $pay_type = '')
    {
        $order = [
            'out_trade_no' => $order_sn . '_' . makeRandStr(6),
            'openid' => $openid,
            'total_fee' => $total*100,  // **单位：分**
            'body' => '商品订单',
            'attach' => $gateway
        ];
        if($pay_type == 'recharge') {
            $order['body'] = '会员充值';
        }
        if($pay_type == 'vip') {
            $order['body'] = 'vip会员购买';
        }

        switch ($gateway)
        {
            case 'mp' :
                return YPay::wechat($this->wxConfig)->mp($order);
                break;
            case 'miniapp' :
                return YPay::wechat($this->wxConfig)->miniapp($order);
                break;
            case 'wap' :
                return YPay::wechat($this->wxConfig)->wap($order);
                break;
            case 'app' :
                return YPay::wechat($this->wxConfig)->app($order);
                break;
            case 'scan' :
                return YPay::wechat($this->wxConfig)->scan($order);
                break;
        }
    }

    /**
     * 获取返回数据
     * @return \Yansongda\Supports\Collection
     * @throws \Yansongda\Pay\Exceptions\InvalidArgumentException
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function wxVerify()
    {
        $wechat = YPay::wechat($this->wxConfig);
        $data = $wechat->verify();
        $wechat->success()->send();
        return $data;
    }

    /**
     * 微信退款
     * @param string $transaction_id
     * @param float $total_fee
     * @param float $refund_fee
     * @param string $refund_desc
     * @param string $refund_type
     * @return \Yansongda\Supports\Collection
     * @throws \Yansongda\Pay\Exceptions\GatewayException
     * @throws \Yansongda\Pay\Exceptions\InvalidArgumentException
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function wxRefund(string $transaction_id = '', float $total_fee = 0, float $refund_fee = 0, string $refund_desc = '', string $refund_type = '')
    {
        $order = [
            'transaction_id' => $transaction_id,
            'out_refund_no' => time(),
            'total_fee' => $total_fee,
            'refund_fee' => $refund_fee,
            'refund_desc' => $refund_desc,
        ];
        if($refund_type == 'miniapp' || $refund_type == 'app') {
            $order['type'] = $refund_type;
        }
        return YPay::wechat($this->wxConfig)->refund($order);
    }

    /**
     * 支付宝支付
     * @param string $order_sn
     * @param float $total
     * @param string $gateway web wap app scan mini
     * @param string $pay_type 空:订单 recharge:充值 vip:vip购买
     * @return \Symfony\Component\HttpFoundation\Response|void|\Yansongda\Supports\Collection
     */
    public function aliPay(string $order_sn = '', float $total = 0, string $gateway = 'web', string $pay_type = '')
    {
        $total = sprintf("%.2f",$total);
        $order = [
            'out_trade_no' => $order_sn . '_' . makeRandStr(6) . '_' . $gateway,
            'total_amount' => $total,  // **单位：元**
            'subject' => '商品订单'
        ];
        if($pay_type == 'recharge') {
            $order['subject'] = '会员充值';
        }
        if($pay_type == 'vip') {
            $order['subject'] = 'vip会员购买';
        }
        switch ($gateway)
        {
            case 'web' :
                return YPay::alipay($this->aliConfig)->web($order);
                break;
            case 'wap' :
                return YPay::alipay($this->aliConfig)->wap($order);
                break;
            case 'app' :
                return YPay::alipay($this->aliConfig)->app($order);
                break;
            case 'scan' :
                return YPay::alipay($this->aliConfig)->scan($order);
                break;
            case 'mini' :
                return YPay::alipay($this->aliConfig)->mini($order);
                break;
        }
    }

    /**
     * 获取返回数据
     * @return \Yansongda\Supports\Collection
     * @throws \Yansongda\Pay\Exceptions\InvalidConfigException
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function aliVerify()
    {
        $alipay = YPay::alipay($this->aliConfig);
        $data = $alipay->verify();
        $alipay->success()->send();
        return $data;
    }

    /**
     * 获取返回数据
     * @return \Yansongda\Supports\Collection
     * @throws \Yansongda\Pay\Exceptions\InvalidConfigException
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function aliReturn()
    {
        $alipay = YPay::alipay($this->aliConfig);
        return $alipay->verify();
    }

    /**
     * 支付宝退款
     * @param string $trade_no
     * @param float $refund_fee
     * @param string $refund_desc
     * @return \Yansongda\Supports\Collection
     * @throws \Yansongda\Pay\Exceptions\GatewayException
     * @throws \Yansongda\Pay\Exceptions\InvalidConfigException
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function aliRefund(string $trade_no = '', float $refund_fee = 0, string $refund_desc = '')
    {
        $order = [
            'trade_no' => $trade_no,
            'refund_amount' => $refund_fee,
            'refund_reason' => $refund_desc,
        ];
        return YPay::alipay($this->aliConfig)->refund($order);
    }

}