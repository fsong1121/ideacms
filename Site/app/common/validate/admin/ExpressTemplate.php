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

class ExpressTemplate extends Validate
{
    protected $rule =   [
        'm_title'  => 'require',
        'm_first_num'  => 'require',
        'm_first_price'  => 'require',
        'm_second_num'  => 'require',
        'm_second_price'  => 'require',
        'm_px'  => 'integer',
        'm_area'  => 'require',
        'm_express_template'  => 'require'
    ];

    protected $message  =   [
        'm_title.require' => '名称为空',
        'm_first_num.require' => '首重为空',
        'm_first_price.require' => '首重运费为空',
        'm_second_num.require' => '续重为空',
        'm_second_price.require' => '续重运费为空',
        'm_px.integer' => '排序无效',
        'm_area.require' => '区域为空',
        'm_express_template.require' => '运费模板为空',
    ];

    protected $scene = [
        'index' => ['m_title','m_first_num','m_first_price','m_second_num','m_second_price','m_px'],
        'price' => ['m_area','m_first_num','m_first_price','m_second_num','m_second_price','m_express_template'],
    ];
}