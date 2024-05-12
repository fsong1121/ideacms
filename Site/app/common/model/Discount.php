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

class Discount extends BaseModel
{
    /**
     * 获取开始时间
     * @param $value
     * @return string
     */
    public function getBDateAttr($value) : string
    {
        return empty($value) ? '' : date("Y-m-d H:i:s", $value);
    }

    /**
     * 获取结束时间
     * @param $value
     * @return string
     */
    public function getEDateAttr($value) : string
    {
        return empty($value) ? '' : date("Y-m-d H:i:s", $value);
    }

    /**
     * 设置开始时间
     * @param $value
     * @return int
     */
    public function setBDateAttr($value) : int
    {
        return empty($value) ? time() : strtotime($value);
    }

    /**
     * 设置结束时间
     * @param $value
     * @return int
     */
    public function setEDateAttr($value) : int
    {
        return empty($value) ? time() : strtotime($value);
    }
}