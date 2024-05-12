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

class Config extends Base
{
    /**
     * 保存网站设置
     * @param $param
     * @return array
     */
    public function saveSite($param) : array
    {
        try {
            $code = "return [
                'url' => '" . $param['m_url'] . "',
                'title' => '" . $param['title'] . "',
                'logo' => '" . $param['logo'] . "',
                'name' => '" . $param['name'] . "',
                'phone' => '" . $param['phone'] . "',
                'tel' => '" . $param['tel'] . "',
                'openid' => '" . $param['openid'] . "',
                'qq' => '" . $param['qq'] . "',
                'template' => '" . $param['template'] . "',
                'keywords' => '" . $param['keywords'] . "',
                'description' => '" . $param['description'] . "',
                'company' => '" . $param['company'] . "',
                'record' => '" . $param['record'] . "',
                'copyright' => '" . $param['copyright'] . "',
                'hot_key' => '" . $param['hot_key'] . "',
                'open_pc' => '" . $param['open_pc'] . "',
                'app_id' => '" . $param['app_id'] . "',
                'api_key' => '" . $param['api_key'] . "',
                'token_ttl' => '" . $param['token_ttl'] . "',
                'refresh_token_ttl' => '" . $param['refresh_token_ttl'] . "',
                'user_reserved_word' => '" . $param['user_reserved_word'] . "',
                'user_recharge' => '" . $param['user_recharge'] . "',
                'user_recharge_gift' => '" . $param['user_recharge_gift'] . "',
                'code_login' => '" . $param['code_login'] . "',
                'other_login' => '" . $param['other_login'] . "',
                'log_days' => '" . $param['log_days'] . "',
                'map_key' => '" . $param['map_key'] . "'
            ]";
            $code = "<?php\n " . $code . ";";
            file_put_contents(config_path() . "site.php", $code);
            if($param['cache_type']=='redis') {
                $code = "return [
                    'host' => '" . $param['redis_host'] . "',
                    'port' => " . $param['redis_port'] . ",
                    'auth' => '" . $param['redis_auth'] . "'
                ]";
                $code = "<?php\n " . $code . ";";
                //file_put_contents(Env::get('config_path') . "redis.php", $code);
            }
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存上传设置
     * @param $param
     * @return array
     */
    public function saveUpload($param) : array
    {
        $denyExt = ["php","php5","php4","php3","php2","html","htm","phtml","pht","jsp","jspa","jspx","jsw","jsv","jspf","jtml","asp","aspx","asa","asax","ascx","ashx","asmx","cer","swf","htaccess"];
        $upType = strtolower($param['uptype']);
        foreach ($denyExt as $key => $value) {
            if(strstr($upType,$value)) {
                return fail('包含非法后缀');
            }
        }
        try {
            $code = "return [
                'uptype' => '" . $param['uptype'] . "',
                'upsize' => '" . $param['upsize'] . "',
                'uplocation' => '" . $param['uplocation'] . "',
                'access_key_id' => '" . $param['access_key_id'] . "',
                'access_key_secret' => '" . $param['access_key_secret'] . "',
                'bucket' => '" . $param['bucket'] . "',
                'domain' => '" . $param['domain'] . "',
                'endpoint' => '" . $param['endpoint'] . "'
            ]";
            $code = "<?php\n " . $code . ";";
            file_put_contents(config_path() . "upload.php", $code);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存商城设置
     * @param $param
     * @return array
     */
    public function saveShop($param) : array
    {
        try {
            $code = "return [
                'default_stock' => '" . $param['default_stock'] . "',
                'warning_stock' => '" . $param['warning_stock'] . "',
                'is_exchange' => '" . $param['is_exchange'] . "',
                'exchange_ratio' => '" . $param['exchange_ratio'] . "',
                'mini_integral' => '" . $param['mini_integral'] . "',
                'use_ratio' => '" . $param['use_ratio'] . "',
                'close_time' => '" . $param['close_time'] . "',
                'send_time' => '" . $param['send_time'] . "',
                'finish_time' => '" . $param['finish_time'] . "',
                'is_free_shipping' => '" . $param['is_free_shipping'] . "',
                'free_price' => '" . $param['free_price'] . "',
                'is_refund' => '" . $param['is_refund'] . "',
                'refund_time' => '" . $param['refund_time'] . "',
                'refund_name' => '" . $param['refund_name'] . "',
                'refund_tel' => '" . $param['refund_tel'] . "',
                'refund_address' => '" . $param['refund_address'] . "',
                'refund_reason1' => '" . $param['refund_reason1'] . "',
                'refund_reason2' => '" . $param['refund_reason2'] . "',
                'is_bill' => '" . $param['is_bill'] . "',
                'bill_time' => '" . $param['bill_time'] . "'
            ]";
            $code = "<?php\n " . $code . ";";
            file_put_contents(config_path() . "shop.php", $code);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存快递设置
     * @param $param
     * @return array
     */
    public function saveExpress($param) : array
    {
        try {
            $expressArr = explode(',', $param['m_express']);
            $code = "return [
                'type' => '" . $param['m_type'] . "',
                'appCode' => '" . $param['m_app_code'] . "',
                'appSecret' => '" . $param['m_app_secret'] . "',
                'list' => [";
            foreach ($expressArr as $value) {
                $expressCell = explode('|', $value);
                $code = $code . "
                    ['name'=>'".$expressCell[0]."','code'=>'".$expressCell[1]."'],";
            }
            $code = $code . "
                ]
            ]";
            $code = "<?php\n " . $code . ";";
            file_put_contents(config_path() . "express.php", $code);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存支付设置
     * @param $param
     * @return array
     */
    public function savePay($param) : array
    {
        try {
            $code = "return [
                'wechat_pay' => [
                    'open' => " . $param['m_wechat_pay'] . ",
                    'mchid' => '" . $param['m_wechat_mchid'] . "',
                    'key' => '" . $param['m_wechat_key'] . "',
                    'apiclient_cert' => '" . $param['m_wechat_apiclient_cert'] . "',
                    'apiclient_key' => '" . $param['m_wechat_apiclient_key'] . "',
                    'notify_url' => '" . $param['m_wechat_notify_url'] . "'
                ],
                'ali_pay' => [
                     'open' => " . $param['m_ali_pay'] . ",
                     'app_id' => '" . $param['m_ali_app_id'] . "',
                     'public_key' => '" . $param['m_ali_public_key'] . "',
                     'private_key' => '" . $param['m_ali_private_key'] . "',
                     'notify_url' => '" . $param['m_ali_notify_url'] . "',
                     'return_url' => '" . $param['m_ali_return_url'] . "',
                 ],
                 'balance_pay' => " . $param['m_balance_pay'] . ",
                 'cod_pay' => " . $param['m_cod_pay'] . "
            ]";
            $code = "<?php\n " . $code . ";";
            file_put_contents(config_path() . "pay.php", $code);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存短信设置
     * @param $param
     * @return array
     */
    public function saveSms($param) : array
    {
        try {
            $code = "return [
                'type' => " . $param['m_type'] . ",
                'appCode' => '" . $param['m_app_code'] . "',
                'appSecret' => '" . $param['m_app_secret'] . "',
                'signId' => '" . $param['m_sign_id'] . "',
                'codeTemplateId' => '" . $param['m_code_template_id'] . "'
            ]";
            $code = "<?php\n " . $code . ";";
            file_put_contents(config_path() . "sms.php", $code);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存微信设置
     * @param $param
     * @return array
     */
    public function saveWechat($param) : array
    {
        try {
            $code = "return [
                'mp' => [
                    'title' => '" . $param['m_title'] . "',
                    'appid' => '" . $param['m_appid'] . "',
                    'appsecret' => '" . $param['m_appsecret'] . "'
                ],
                'miniapp' => [
                    'title' => '" . $param['m_miniapp_title'] . "',
                    'appid' => '" . $param['m_miniapp_appid'] . "',
                    'appsecret' => '" . $param['m_miniapp_appsecret'] . "',
                    'auto_send' => '" . $param['m_auto_send'] . "'
                 ]
            ]";
            $code = "<?php\n " . $code . ";";
            file_put_contents(config_path() . "wechat.php", $code);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存协议设置
     * @param $param
     * @return array
     */
    public function saveAgreement($param) : array
    {
        try {
            $userInfo = str_replace("'", "", $param['m_user_info']);
            $privacyInfo = str_replace("'", "", $param['m_privacy_info']);
            $code = "return [
                'user' => '" . $userInfo . "',
                'privacy' => '" . $privacyInfo . "'
            ]";
            $code = "<?php\n " . $code . ";";
            file_put_contents(config_path() . "agreement.php", $code);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存消息设置
     * @param $param
     * @return array
     */
    public function saveMessage($param) : array
    {
        try {
            $code = "return [
                'sms_user_pay' => '" . $param['sms_user_pay'] . "',
                'sms_user_send' => '" . $param['sms_user_send'] . "',
                'sms_user_refund' => '" . $param['sms_user_refund'] . "',
                'sms_user_pay_id' => '" . $param['sms_user_pay_id'] . "',
                'sms_user_send_id' => '" . $param['sms_user_send_id'] . "',
                'sms_user_refund_id' => '" . $param['sms_user_refund_id'] . "',
                'sms_store_pay' => '" . $param['sms_store_pay'] . "',
                'sms_store_refund' => '" . $param['sms_store_refund'] . "',
                'sms_store_pay_id' => '" . $param['sms_store_pay_id'] . "',
                'sms_store_refund_id' => '" . $param['sms_store_refund_id'] . "',
                'gzh_user_pay' => '" . $param['gzh_user_pay'] . "',
                'gzh_user_send' => '" . $param['gzh_user_send'] . "',
                'gzh_user_refund' => '" . $param['gzh_user_refund'] . "',
                'gzh_user_pay_id' => '" . $param['gzh_user_pay_id'] . "',
                'gzh_user_send_id' => '" . $param['gzh_user_send_id'] . "',
                'gzh_user_refund_id' => '" . $param['gzh_user_refund_id'] . "',
                'gzh_store_pay' => '" . $param['gzh_store_pay'] . "',
                'gzh_store_refund' => '" . $param['gzh_store_refund'] . "',
                'gzh_store_pay_id' => '" . $param['gzh_store_pay_id'] . "',
                'gzh_store_refund_id' => '" . $param['gzh_store_refund_id'] . "',
                'miniapp_user_pay' => '" . $param['miniapp_user_pay'] . "',
                'miniapp_user_send' => '" . $param['miniapp_user_send'] . "',
                'miniapp_user_refund' => '" . $param['miniapp_user_refund'] . "',
                'miniapp_user_pay_id' => '" . $param['miniapp_user_pay_id'] . "',
                'miniapp_user_send_id' => '" . $param['miniapp_user_send_id'] . "',
                'miniapp_user_refund_id' => '" . $param['miniapp_user_refund_id'] . "'
            ]";
            $code = "<?php\n " . $code . ";";
            file_put_contents(config_path() . "message.php", $code);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

}