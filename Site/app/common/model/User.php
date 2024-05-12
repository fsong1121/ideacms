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

class User extends BaseModel
{
    /**
     * 获取会员等级名称
     * @param $value
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLevelIdAttr($value) : string
    {
        $userLevel = Db::name('user_level')->where('id',$value)->find();
        return empty($userLevel) ? '普通会员' : $userLevel['title'];
    }

    /**
     * 获取会员折扣
     * @param $value
     * @param $data
     * @return float|int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLevelRateAttr($value,$data)
    {
        $userLevel = Db::name('user_level')->where('id',$data['level_id'])->find();
        return empty($userLevel) ? 1 : $userLevel['rebate']/100;
    }

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
     * 加密密码
     * @param $value
     * @return string
     */
    public function setPwdAttr($value) : string
    {
        return makePassword($value);
    }

}