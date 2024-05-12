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

class Service extends Validate
{
    protected $rule =   [
        'm_title'  => 'require',
        'm_info'   => 'require',
        'm_px'   => 'require|integer'
    ];

    protected $message  =   [
        'm_title.require' => '名称为空',
        'm_info.require' => '描述为空',
        'm_px.require' => '排序为空',
        'm_px.integer' => '排序不为整数',
    ];
}