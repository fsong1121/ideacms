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

namespace app\common\model;

class Order extends BaseModel
{
    /**
     * 获取添加时间
     * @param $value
     * @return string
     */
    public function getAddDateAttr($value) : string
    {
        return empty($value) ? '' : date("Y-m-d H:i:s", $value);
    }

    /**
     * 获取支付时间
     * @param $value
     * @return string
     */
    public function getPayDateAttr($value) : string
    {
        return empty($value) ? '' : date("Y-m-d H:i:s", $value);
    }

    /**
     * 获取发货时间
     * @param $value
     * @return string
     */
    public function getExpressDateAttr($value) : string
    {
        return empty($value) ? '' : date("Y-m-d H:i:s", $value);
    }

    /**
     * 获取支付方式
     * @param $value
     * @param $data
     * @return string
     */
    public function getPayTypeAttr($value,$data) : string
    {
        if($data['order_type'] == 'integral') {
            return '积分兑换';
        } else {
            $type = config('order.pay_type');
            return $type[$value] ?? '';
        }
    }

    /**
     * 获取配送方式
     * @param $value
     * @return string
     */
    public function getSendTypeAttr($value) : string
    {
        $data = config('order.send_type');
        return $data[$value] ?? '';
    }

    /**
     * 获取订单状态
     * @param $value
     * @param $data
     * @return string
     */
    public function getOrderStateAttr($value,$data) : string
    {
        $stateArr = config('order.order_state');
        $res = $stateArr[$value] ?? '';
        if($res == '已取消' && $data['refund_state'] > 0) {
            $res = '已取消(退款)';
        }
        return $res;
    }

    /**
     * 获取订单来源
     * @param $value
     * @return string
     */
    public function getTerminalAttr($value) : string
    {
        $data = config('order.terminal');
        return $data[$value] ?? '';
    }

    /**
     * 获取发货单号
     * @param $value
     * @return string
     */
    public function getExpressSnAttr($value) : string
    {
        return empty($value) ? '' : $value;
    }

    /**
     * 获取发货说明
     * @param $value
     * @return string
     */
    public function getExpressInfoAttr($value) : string
    {
        return empty($value) ? '' : $value;
    }

}