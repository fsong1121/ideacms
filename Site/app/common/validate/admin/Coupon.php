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

class Coupon extends Validate
{
    protected $rule =   [
        'm_title'  => 'require',
        'm_min_price'   => 'require',
        'm_cut_price'   => 'require',
        'm_amount'   => 'integer',
        'm_per_amount'   => 'integer',
        'b_date'  => 'date',
        'e_date'  => 'date',
        'm_use_type'   => 'checkUseType',
    ];

    protected $message  =   [
        'm_title.require' => '名称为空',
        'm_min_price.require' => '消费门槛为空',
        'm_cut_price.require' => '优惠金额为空',
        'm_amount.integer' => '发放数量不正确',
        'm_per_amount.integer' => '领取上限不正确',
        'b_date.date' => '开始时间不正确',
        'e_date.date' => '结束时间不正确',
    ];

    /**
     * 检查使用类型
     * @param $value
     * @param $rule
     * @param array $data
     * @return bool|string
     */
    protected function checkUseType($value,$rule,$data=[])
    {
        if(empty($data['m_goods_ids']) && $value == 1){
            return '请选择商品';
        }
        return true;
    }
}