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

class GoodsComment extends BaseModel
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
     * 获取回复时间
     * @param $value
     * @return string
     */
    public function getReplyDateAttr($value) : string
    {
        return empty($value) ? '' : date("Y-m-d H:i:s", $value);
    }

    /**
     * 获取图片数组
     * @param $value
     * @return array|string[]
     */
    public function getPicAttr($value)
    {
        return empty($value) ? [] : explode(",",$value);
    }

}