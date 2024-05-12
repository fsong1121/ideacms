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
namespace app\admin\controller;

use app\common\logic\admin\Config as ConfigLogic;
use app\common\validate\admin\Config as ConfigValidate;
use think\facade\Request;
use think\response\Json;

class Config extends Base
{
    /**
     * 网站设置
     * @return string
     */
    public function site() : string
    {
        $this->assign('site', config('site'));
        return $this->fetch('/config_site');
    }

    /**
     * 保存网站设置
     */
    public function saveSite() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new ConfigValidate();
            $result = $validate->scene('site')->check($param);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $configLogic = new ConfigLogic();
                $data = $configLogic->saveSite($param);
                return json($data);
            }
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 上传设置
     * @return string
     */
    public function upload() : string
    {
        $this->assign('upload', config('upload'));
        return $this->fetch('/config_upload');
    }

    /**
     * 保存上传设置
     */
    public function saveUpload() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new ConfigValidate();
            $result = $validate->scene('upload')->check($param);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $configLogic = new ConfigLogic();
                $data = $configLogic->saveUpload($param);
                return json($data);
            }
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 交易设置
     * @return string
     */
    public function shop() : string
    {
        $this->assign('shop', config('shop'));
        return $this->fetch('/config_shop');
    }

    /**
     * 保存商城设置
     */
    public function saveShop() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new ConfigValidate();
            $result = $validate->scene('shop')->check($param);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $configLogic = new ConfigLogic();
                $data = $configLogic->saveShop($param);
                return json($data);
            }
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 快递设置
     * @return string
     */
    public function express() : string
    {
        $expressStr = [];
        $expressList = config('express.list');
        foreach ($expressList as $value) {
            array_push($expressStr,$value['name'] . "|" . $value['code']);
        }
        $this->assign('express', config('express'));
        $this->assign('expressStr', implode(PHP_EOL, $expressStr));
        return $this->fetch('/config_express');
    }

    /**
     * 保存快递设置
     */
    public function saveExpress() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new ConfigValidate();
            $result = $validate->scene('express')->check($param);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $configLogic = new ConfigLogic();
                $data = $configLogic->saveExpress($param);
                return json($data);
            }
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 支付设置
     * @return string
     */
    public function pay() : string
    {
        $this->assign('pay', config('pay'));
        return $this->fetch('/config_pay');
    }

    /**
     * 保存支付设置
     */
    public function savePay() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $configLogic = new ConfigLogic();
            $data = $configLogic->savePay($param);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 短信设置
     * @return string
     */
    public function sms() : string
    {
        $this->assign('sms', config('sms'));
        return $this->fetch('/config_sms');
    }

    /**
     * 保存短信设置
     */
    public function saveSms() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new ConfigValidate();
            $result = $validate->scene('sms')->check($param);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $configLogic = new ConfigLogic();
                $data = $configLogic->saveSms($param);
                return json($data);
            }
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 微信设置
     * @return string
     */
    public function wechat() : string
    {
        $this->assign('wechat', config('wechat'));
        return $this->fetch('/config_wechat');
    }

    /**
     * 保存微信设置
     */
    public function saveWechat() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new ConfigValidate();
            $result = $validate->scene('wechat')->check($param);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $configLogic = new ConfigLogic();
                $data = $configLogic->saveWechat($param);
                return json($data);
            }
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 协议设置
     * @return string
     */
    public function agreement() : string
    {
        return $this->fetch('/config_agreement');
    }

    /**
     * 保存协议设置
     */
    public function saveAgreement() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $configLogic = new ConfigLogic();
            $data = $configLogic->saveAgreement($param);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 消息设置
     * @return string
     */
    public function message() : string
    {
        $this->assign('message', config('message'));
        return $this->fetch('/config_message');
    }

    /**
     * 保存消息设置
     */
    public function saveMessage() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $configLogic = new ConfigLogic();
            $data = $configLogic->saveMessage($param);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

}