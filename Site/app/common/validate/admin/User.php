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

class User extends Validate
{
    protected $rule =   [
        'm_uid'  => 'require',
        'm_pwd'   => 'require',
        'm_title'   => 'require',
        'm_growth'   => 'integer',
        'm_rebate'   => 'integer',
        'm_px'   => 'integer',
        'm_fee'   => 'require',
        'password'   => 'require|confirm',
    ];

    protected $message  =   [
        'm_uid.require' => '用户名为空',
        'm_pwd.require' => '密码为空',
        'm_title.require' => '名称为空',
        'm_growth.integer' => '成长值无效',
        'm_rebate.integer' => '折扣无效',
        'm_px.integer' => '排序无效',
        'm_fee.require' => '调整数量为空',
        'password.require' => '新密码为空',
        'password.confirm' => '两次密码不同',
    ];

    protected $scene = [
        'info' => ['m_uid','m_pwd'],
        'level' => ['m_title','m_growth','m_rebate','m_px'],
        'label' => ['m_title','m_px'],
        'editNum' => ['m_fee'],
        'editPwd' => ['password'],
    ];
}