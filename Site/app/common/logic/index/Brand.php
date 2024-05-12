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

use app\common\model\Brand as BrandModel;
use app\common\logic\BaseLogic;
use think\facade\Db;

class Brand extends BaseLogic
{
    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = BrandModel::where('is_show',1)
                ->order(['is_top'=>'desc','sequence'=>'desc'])
                ->paginate($param['size'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key]['goods_count'] = Db::name('goods')->where('brand_id',$value['id'])->count();
                $list['data'][$key]['sales'] = Db::name('goods')->where('brand_id',$value['id'])->sum('sales');
            }
            return [
                'code' => 0,
                'msg' => '',
                'count' => $list['total'],
                'data' => $list['data']
            ];
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

}