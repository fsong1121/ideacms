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

class Discount extends Validate
{
    protected $rule =   [
        'm_title'  => 'require',
        'm_type' => 'in:0,1',
        'm_min_price'   => 'require',
        'm_send_type'   => 'checkSendType',
        'b_date'  => 'date',
        'e_date'  => 'date',
        'm_use_type'   => 'checkUseType'
    ];

    protected $message  =   [
        'm_title.require' => '名称为空',
        'm_type.in' => '活动类型不正确',
        'm_min_price.require' => '优惠门槛为空',
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
    protected function checkUseType($value,$rule,array $data=[])
    {
        if(empty($data['m_goods_ids']) && $value == 1){
            return '请选择商品';
        }
        return true;
    }

    /**
     * 检查优惠内容
     * @param $value
     * @param $rule
     * @param array $data
     * @return bool|string
     */
    protected function checkSendType($value,$rule,array $data=[])
    {
        switch ($value)
        {
            case 0:
                if(is_numeric($data['m_send_price']) === false || $data['m_send_price'] < 0) {
                    return '直减金额不正确';
                }
                break;
            case 1:
                if(is_numeric($data['m_send_rebate']) === false || ($data['m_send_rebate'] < 0 || $data['m_send_rebate'] > 10)) {
                    return '优惠折扣不正确';
                }
                break;
            case 2:
                if(is_numeric($data['m_send_integral']) === false || $data['m_send_integral'] < 0) {
                    return '赠送积分不正确';
                }
                break;
            default:
                if(is_numeric($data['m_send_coupon_id']) === false || $data['m_send_coupon_id'] <= 0) {
                    return '请选择优惠券';
                }
        }
        return true;
    }
}