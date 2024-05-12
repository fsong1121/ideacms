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

use think\facade\Db;

/**
 * 登录日志
 */
class LoginLog
{
    //行为扩展的执行入口必须是run
    public function handle($param) : array
    {
        try {
            $data = [
                'type' => $param['type'],
                'uid' => $param['uid'],
                'ip' => $param['ip'],
                'info' => $param['info'],
                'state' => $param['state'],
                'data' => json_encode($param['data']),
                'add_date' => time()
            ];
            Db::name('login_log')->insert($data);
            return success();
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

}