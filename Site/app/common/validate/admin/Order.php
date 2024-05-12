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

class Order extends Validate
{
    protected $rule =   [
        'm_express_price'  => 'require',
        'm_trim_price'   => 'require',
    ];

    protected $message  =   [
        'm_express_price' => '调整运费为空',
        'm_trim_price' => '价格调整为空',
    ];

    protected $scene = [
        'editPrice' => ['m_express_price','m_trim_price'],
    ];
}