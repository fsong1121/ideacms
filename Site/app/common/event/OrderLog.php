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
 * 订单日志
 */
class OrderLog
{
    //行为扩展的执行入口必须是run
    public function handle($param) : array
    {
        try {
            $data = [
                'type' => $param['type'],
                'order_id' => $param['order_id'],
                'user_id' => $param['user_id'],
                'info' => $param['info'],
                'add_date' => time()
            ];
            Db::name('order_log')->insert($data);
            return success();
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

}