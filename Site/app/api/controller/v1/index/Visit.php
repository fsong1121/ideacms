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
namespace app\api\controller\v1\index;

use app\common\logic\index\Visit as VisitLogic;
use think\response\Json;
use think\facade\Request;

class Visit extends Base
{
    /**
     * 添加PV
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addPv() : Json
    {
        if(Request::isPost()) {
            $res = $this->setParam(Request::param());
            if ($res['code'] == 0) {
                $logic = new VisitLogic();
                $res = $logic->addPv($res['data']);
            }
            return json($res);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }
}