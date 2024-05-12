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

use think\response\Json;

class Config extends Base
{
    /**
     * 获取站点信息
     * @return Json
     */
    public function site() : Json
    {
        $data['title'] = config('site.title');
        $data['url'] = config('site.url');
        $data['logo'] = config('site.logo');
        $data['phone'] = config('site.phone');
        $data['tel'] = config('site.tel');
        $data['template'] = config('site.template');
        $data['keywords'] = config('site.keywords');
        $data['description'] = config('site.description');
        $data['company'] = config('site.company');
        $data['record'] = config('site.record');
        $data['copyright'] = config('site.copyright');
        $data['code_login'] = config('site.code_login');
        $data['other_login'] = config('site.other_login');
        $data['hot_key'] = explode('|',config('site.hot_key'));
        $data['map_key'] = config('site.map_key');
        $data['shop'] = [
            'is_exchange' => config('shop.is_exchange'),
            'is_refund' => config('shop.is_refund'),
            'refund_name' => config('shop.refund_name'),
            'refund_tel' => config('shop.refund_tel'),
            'refund_address' => config('shop.refund_address'),
            'refund_reason1' => explode('/',config('shop.refund_reason1')),
            'refund_reason2' => explode('/',config('shop.refund_reason2')),
            'is_bill' => config('shop.is_bill')
        ];
        $data['mp_appid'] = config('wechat.mp.appid');
        $data['wechat_pay'] = config('pay.wechat_pay.open');
        return json(success($data));
    }

    /**
     * 获取注册协议
     * @return Json
     */
    public function agreement() : Json
    {
        return json(success(config('agreement')));
    }

    /**
     * 获取消息设置
     * @return Json
     */
    public function message() : Json
    {
        return json(success(config('message')));
    }

    /**
     * 获取消息设置
     * @return Json
     */
    public function shop() : Json
    {
        $shop = config('shop');
        $shop['refund_reason1'] = explode(',',$shop['refund_reason1']);
        $shop['refund_reason2'] = explode(',',$shop['refund_reason2']);
        return json(success($shop));
    }
}