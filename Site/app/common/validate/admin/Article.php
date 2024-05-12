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

class Article extends Validate
{
    protected $rule =   [
        'm_title'  => 'require',
        'm_cat'   => 'require',
        'm_hits'   => 'integer',
        'm_zan'   => 'integer',
        'm_px'   => 'integer'
    ];

    protected $message  =   [
        'm_title.require' => '名称为空',
        'm_cat.require' => '分类为空',
        'm_hits.integer' => '人气不为整数',
        'm_zan.integer' => '点赞数不为整数',
        'm_px.integer' => '排序不为整数',
    ];
}