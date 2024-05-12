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
namespace app\common\event;

use think\facade\Event;

/**
 * 会员注册成功
 */
class RegSuccess
{
    /**
     * 行为扩展的执行入口必须是run
     * @param array $param
     * @return array
     */
    public function handle(array $param = []) : array
    {
        try {
            //是否注册后成为分销商
            Event::trigger('distributionReg',$param);
            return success();
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

}