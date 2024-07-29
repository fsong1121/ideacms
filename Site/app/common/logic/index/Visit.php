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
namespace app\common\logic\index;

use app\common\logic\BaseLogic;
use app\common\model\Visit as VisitModel;

class Visit extends BaseLogic
{
    /**
     * 添加PV
     * @param array $param
     * @return array
     */
    public function addPv(array $param) : array
    {
        try {
            VisitModel::create([
                'user_id'  => $param['user_id'] ?? 0,
                'goods_id' => $param['goods_id'] ?? 0,
                'url' => $param['url'] ?? '',
                'platform' => $param['platform'] ?? '',
                'model' => $param['model'] ?? '',
                'ip' => $param['ip'] ?? '',
                'add_date' => time()
            ]);
            return success();
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

}