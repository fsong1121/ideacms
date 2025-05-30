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

namespace app\common\service;

class Sms
{
    /**
     * 发送短信
     * @param string $tel
     * @param string $tpl
     * @param array $param
     * @return mixed|void
     */
    public function sendSms(string $tel = '', string $tpl = '', array $param = [])
    {
        $type = config('sms.type');
        $appId = config('sms.appCode');
        $appSecret = config('sms.appSecret');
        $signId = config('sms.signId');
        $time = time();
        $randStr = makeRandStr();

        switch ($type)
        {
            //腾讯云短信
            case 0 :
                $sign = hash('sha256','appkey='.$appSecret.'&random='.$randStr.'&time='.$time.'&mobile='.$tel);
                $sendArr = [
                    'tel' => [
                        'nationcode' => '86',
                        'mobile' => $tel
                    ],
                    'sign' => $signId,
                    'tpl_id' => $tpl,
                    'params' => $param,
                    'sig' => $sign,
                    'time' => $time,
                    'extend' => '',
                    'ext' => ''
                ];
                return curlPost('https://yun.tim.qq.com/v5/tlssmssvr/sendsms?sdkappid='.$appId.'&random='.$randStr,$sendArr);
                break;
        }
    }

}