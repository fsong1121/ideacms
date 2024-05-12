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

use app\common\service\Auth as AuthService;
use app\common\validate\admin\Login as LoginValidate;
use think\facade\Request;
use think\response\Json;

class Login extends Base
{
    /**
     * 登录页
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/login');
    }

    /**
     * 登录
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function login() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $params = Request::param();
            $validate = new LoginValidate();
            $result = $validate->check($params);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $auth = new AuthService();
                return json($auth->login('admin',$params));
            }
        } else {
            return json(fail('非法请求被禁止'));
        }
    }

    /**
     * 退出
     * @return Json
     */
    public function loginOut() : Json
    {
        $auth = new AuthService();
        $auth->loginOut('admin');
        return json(success());
    }
}