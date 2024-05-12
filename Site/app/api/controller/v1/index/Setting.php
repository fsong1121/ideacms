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

use app\common\model\MobileMenu as MobileMenuModel;
use think\facade\Cache;
use think\response\Json;

class Setting extends Base
{
    /**
     * 获取手机端设置
     * @return Json
     */
    public function mobile() : Json
    {
        $data = config('setting');
        if(!empty($data['card_pic'])) {
            $data['card_pic'] = getPic($data['card_pic']);
        }
        return json(success($data));
    }

    /**
     * 获取金刚区菜单
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function mobileMenu() : Json
    {
        $list = Cache::get('mobileMenu',[]);
        if(empty($list)) {
            $list = MobileMenuModel::where('is_show',1)
                ->order(['sequence'=>'desc','id'=>'desc'])
                ->select()
                ->toArray();
            Cache::set('mobileMenu',$list);
        }
        return json(success($list));
    }
}