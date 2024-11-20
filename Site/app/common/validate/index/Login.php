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

namespace app\common\validate\index;

use think\Validate;

class Login extends Validate
{
    protected $rule =   [
        'm_uid'  => 'require',
        'm_tel'  => 'require',
        'm_pwd'   => 'require',
        'm_captcha' => 'require|captcha',
        'm_code'   => 'require',
    ];

    protected $message  =   [
        'm_uid.require' => '用户名为空',
        'm_tel.require' => '手机号为空',
        'm_pwd.require' => '密码为空',
        'm_captcha.require'   => '验证码为空',
        'm_captcha.captcha'  => '验证码错误',
        'm_code.require' => '短信验证码为空',
    ];

    protected $scene = [
        'login' => ['m_uid','m_pwd','m_captcha'],
        'smsLogin' => ['m_tel','m_code'],
    ];
}