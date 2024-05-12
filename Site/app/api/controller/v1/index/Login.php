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

use app\common\logic\index\Login as LoginLogic;
use think\response\Json;
use think\facade\Request;

class Login extends Base
{
    /**
     * 账号登录
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login() : Json
    {
        if(Request::isPost()) {
            $res = $this->setParam(Request::param(), 0);
            if ($res['code'] == 0) {
                $logic = new LoginLogic();
                $res = $logic->login($res['data']);
            }
            return json($res);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 发送手机验证码
     * @return Json
     * @throws \think\api\Exception
     */
    public function sendSmsCode() : Json
    {
        if(Request::isPost()) {
            $param = Request::param();
            $logic = new LoginLogic();
            return json($logic->sendSmsCode($param));
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 手机验证码登录
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function smsCodeLogin() : Json
    {
        if(Request::isPost()) {
            $res = $this->setParam(Request::param(),0);
            if($res['code'] == 0) {
                $logic = new LoginLogic();
                $res = $logic->smsCodeLogin($res['data']);
            }
            return json($res);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 微信登录
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function miniappLogin() : Json
    {
        if(Request::isPost()) {
            $res = $this->setParam(Request::param(),0);
            if($res['code'] == 0) {
                $logic = new LoginLogic();
                $res = $logic->miniappLogin($res['data']);
            }
            return json($res);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 判断是否登录
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function checkLogin() : Json
    {
        if(Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    $logic = new LoginLogic();
                    $res = $logic->checkLogin($param);
                } else {
                    $res = fail('请先登录',401);
                }
            }
            return json($res);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 退出登录
     * @return Json
     */
    public function loginOut() : Json
    {
        $logic = new LoginLogic();
        return json($logic->loginOut($this->accessToken));
    }

    /**
     * 获取微信小程序openid
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOpenId() : Json
    {
        if(Request::isPost()) {
            $res = $this->setParam(Request::param(),0);
            if($res['code'] == 0) {
                $logic = new LoginLogic();
                $res = $logic->getOpenId($res['data']);
            }
            return json($res);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

}