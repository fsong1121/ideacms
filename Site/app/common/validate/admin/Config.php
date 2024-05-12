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
namespace app\common\validate\admin;

use think\Validate;

class Config extends Validate
{
    protected $rule =   [
        'm_url'  => 'require|url',
        'title'  => 'require',
        'default_stock'  => 'require|integer',
        'warning_stock'  => 'require|integer',
        'app_id'  => 'require|length:16',
        'api_key'  => 'require|length:16',
        'user_min_cash'  => 'require',
        'user_cash_rate'  => 'require',
        'user_recharge_gift'  => 'require',
        'uptype'  => 'require',
        'mini_integral'   => 'require|number',
        'use_ratio' => 'require|number',
        'free_price'   => 'require',
        'm_appid'  => 'require',
        'm_appsecret'  => 'require',
        'm_miniapp_appid'  => 'require',
        'm_miniapp_appsecret'  => 'require',
        'm_app_code' => 'require',
        'm_app_secret' => 'checkSecret',
        'm_sign_id' => 'require',
    ];

    protected $message  =   [
        'm_url.require' => '网站地址为空',
        'm_url.url' => '网站地址错误',
        'title.require' => '网站名称为空',
        'default_stock.require' => '默认库存数为空',
        'default_stock.integer' => '默认库存数不正确',
        'warning_stock.require' => '库存预警数为空',
        'warning_stock.integer' => '库存预警数不正确',
        'app_id.require' => 'AppID为空',
        'app_id.length' => 'AppID不为16位',
        'api_key.require' => 'ApiKey为空',
        'api_key.length' => 'ApiKey不为16位',
        'user_min_cash.require' => '会员最低提现额为空',
        'user_cash_rate.require' => '会员提现费率为空',
        'user_recharge_gift.require' => '充值赠送比例为空',
        'uptype.require' => '允许上传类型为空',
        'mini_integral.require' => '最低使用积分为空',
        'mini_integral.number' => '最低使用积分不为数字',
        'use_ratio.require' => '使用比例为空',
        'use_ratio.number' => '使用比例不为数字',
        'free_price.require' => '包邮金额为空',
        'm_appid.require' => 'AppID为空',
        'm_appsecret.require' => 'AppSecret为空',
        'm_miniapp_appid.require' => '小程序AppID为空',
        'm_miniapp_appsecret.require' => '小程序AppSecret为空',
        'm_app_code.require' => 'AppCode为空',
        'm_sign_id.require' => '签名为空',
    ];

    /**
     * 验证短信秘钥是否必须
     * @param $value
     * @param $rule
     * @param array $data
     * @return bool|string
     */
    protected function checkSecret($value,$rule,array $data=[])
    {
        if(empty($data['m_app_secret']) && $data['m_type'] > 0) {
            return 'appSecret为空';
        }
        return true;
    }

    protected $scene = [
        'site' => ['m_url','title','app_id','api_key','user_recharge_gift'],
        'upload' => ['uptype'],
        'shop' => ['default_stock','warning_stock','mini_integral','use_ratio','free_price'],
        'express' => ['m_app_code','m_app_secret'],
        'sms' => ['m_app_code','m_app_secret','m_sign_id'],
        'wechat' => ['m_appid','m_appsecret','m_miniapp_appid','m_miniapp_appsecret'],
    ];
}