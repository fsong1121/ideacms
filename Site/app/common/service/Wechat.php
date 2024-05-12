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

use think\facade\Cache;

class Wechat
{
    protected array $config = [];

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->config = config('wechat');
    }

    /**
     * 发送公众号消息
     * @param array $param
     * @return array
     */
    public function sendMsg(array $param = []) : array
    {
        $postUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $this->getAccessToken('gzh','sendMsg');
        $sendData['template_id'] = $param['template_id'];
        $sendData['touser'] = $param['touser'];
        $sendData['data'] = $param['data'];
        if(isset($param['url'])) {
            $sendData['url'] = $param['url'];
        }
        return curlPost($postUrl,$sendData);
    }

    /**
     * 发送小程序消息
     * @param array $param
     * @return array
     */
    public function sendMiniMsg(array $param = []) : array
    {
        $postUrl = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=' . $this->getAccessToken('miniapp','sendMsg');
        $sendData['template_id'] = $param['template_id'];
        $sendData['page'] = $param['page'];
        $sendData['touser'] = $param['touser'];
        $sendData['data'] = $param['data'];
        return curlPost($postUrl,$sendData);
    }

    /**
     * 小程序上传发货信息
     * @param array $param
     * @return array
     */
    public function uploadShipping(array $param = []) : array
    {
        $postUrl = 'https://api.weixin.qq.com/wxa/sec/order/upload_shipping_info?access_token=' . $this->getAccessToken('miniapp','uploadShipping');
        $sendData['order_key'] = $param['order_key'];
        $sendData['delivery_mode'] = 1; //1为统一发货
        $sendData['shipping_list'][0] = $param['shipping_list'];
        $sendData['logistics_type'] = $param['logistics_type']; //1快递，2同城配送，3虚拟商品，4用户自提
        $sendData['upload_time'] = date(DATE_RFC3339);
        $sendData['payer'] = $param['payer'];
        return curlPost($postUrl, $sendData);
    }

    /**
     * 获取accessToken
     * @param string $type miniapp为小程序，其他为公众号
     * @param string $name 不同的业务设置不同的name
     * @return string
     */
    public function getAccessToken(string $type = '',string $name = '') : string
    {
        $appId = $type == 'miniapp' ? $this->config['miniapp']['appid'] : $this->config['mp']['appid'];
        $appSecret = $type == 'miniapp' ? $this->config['miniapp']['appsecret'] : $this->config['mp']['appsecret'];
        $accessToken = Cache::get($type . $name . '_access_token','');
        if(empty($accessToken)) {
            $tokenUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appId . '&secret=' . $appSecret;
            $data = curlGet($tokenUrl);
            if (isset($data['access_token'])) {
                $accessToken = $data['access_token'];
                Cache::set($type . $name . '_access_token',$accessToken,6000);
            }
        }
        return $accessToken;
    }

    /**
     * 公众号登录
     * @param string $code
     * @return array
     */
    public function getMpOpenId(string $code = '') : array
    {
        $appId = $this->config['mp']['appid'];
        $appSecret = $this->config['mp']['appsecret'];
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appId."&secret=".$appSecret."&code=".$code."&grant_type=authorization_code";
        $res = curlGet($url);
        if(isset($res['openid'])) {
            $data = [
                'access_token' => $res['access_token'],
                'openid' => $res['openid'],
                'unionid' => ''
            ];
            if(isset($res['unionid'])) {
                $data['unionid'] = $res['unionid'];
            }
            return success($data);
        } else {
            return fail($res['errmsg']);
        }
    }

}