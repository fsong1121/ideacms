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

use app\common\logic\Message as MessageLogic;

/**
 * 发送消息
 */
class SendMessage
{
    /**
     * 行为扩展的执行入口必须是run
     * @param array $param
     * @return array
     * @throws \think\api\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function handle(array $param = []) : array
    {
        $logic = new MessageLogic();
        return $logic->send($param['sn'],$param['type'],$param['state']);
    }

}