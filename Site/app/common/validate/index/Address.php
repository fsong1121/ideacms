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

class Address extends Validate
{
    protected $rule =   [
        'province'  => 'require',
        'city'  => 'require',
        'county'  => 'require',
        'address' => 'require',
        'name'  => 'require',
        'tel'   => 'require|mobile'
    ];

    protected $message  =   [
        'province.require' => '请选择地区',
        'city.require' => '请选择地区',
        'county.require' => '请选择地区',
        'address.require' => '详细地址为空',
        'name.require' => '收货人为空',
        'tel.require' => '手机号为空',
        'tel.mobile' => '手机号错误',
    ];
}