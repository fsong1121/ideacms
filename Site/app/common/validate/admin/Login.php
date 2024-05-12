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

class Login extends Validate
{
    protected $rule =   [
        'm_uid'  => 'require',
        'm_pwd'   => 'require',
        'm_code' => 'require|captcha',
    ];

    protected $message  =   [
        'm_uid.require' => '用户名为空',
        'm_pwd.require' => '密码为空',
        'm_code.require'   => '验证码为空',
        'm_code.captcha'  => '验证码错误',
    ];
}