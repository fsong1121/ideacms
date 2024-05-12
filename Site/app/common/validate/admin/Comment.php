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

class Comment extends Validate
{
    protected $rule =   [
        'm_goods_id'   => 'require|integer',
        'm_user_name'  => 'require',
        'm_info'   => 'require',
        'm_reply_info'   => 'require',
    ];

    protected $message  =   [
        'm_goods_id.require' => '商品为空',
        'm_goods_id.integer' => '商品编号不正确',
        'm_user_name.require' => '会员昵称为空',
        'm_info.require' => '评价内容为空',
        'm_reply_info.require' => '回复内容为空',
    ];

    protected $scene = [
        'add' => ['m_goods_id','m_user_name','m_info'],
        'reply' => ['m_reply_info'],
    ];
}