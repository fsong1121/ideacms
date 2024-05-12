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

use think\facade\Db;

class IntegralDetail extends BaseModel
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
     * 获取会员名称
     * @param $value
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserIdAttr($value) : string
    {
        $str = '['.$value.']';
        $user = Db::name('user')->where('id',$value)->find();
        if(!empty($user)) {
            $str = $user['uid'] . $str;
        }
        return $str;
    }

}