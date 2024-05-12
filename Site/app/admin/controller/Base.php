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

use app\BaseController;
use app\common\logic\admin\Base as baseLogic;
use app\common\service\Auth as AuthService;
use think\exception\HttpResponseException;
use think\facade\Request;
use think\facade\View;
use think\facade\Db;

class Base extends BaseController
{
    protected string $uuid;
    protected int $uid;
    protected int $roleId;
    protected int $perPage;

    /**
     * 初始化
     * @return string|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function initialize()
    {
        $this->perPage = 20;
        $whiteController = ['login']; //控制器白名单，不用验证登录
        $controller = parse_name(Request::controller());
        $action = parse_name(Request::action());
        if(!in_array($controller, $whiteController)) {
            $auth = new AuthService();
            if ($auth->checkLogin('admin')) {
                //判断角色权限
                $logic = new baseLogic();
                $roleAuth = $logic->checkRoleAuth($auth->uuid, $controller, $action);
                if ($roleAuth['code'] == 0) {
                    $this->uuid = $auth->uuid;
                    $manager = Db::name('admin')->where('uuid', $this->uuid)->find();
                    if(!empty($manager)) {
                        $this->uid = $manager['id'];
                        $this->roleId = $manager['role_id'];
                    }
                    $this->assign('manager', $manager);
                } else {
                    abort(403,'权限不足');
                }
            } else {
                $this->redirect(url('admin/login/index')->build());
            }
        }
    }

    /**
     * 模板赋值
     * @param mixed ...$vars
     */
    protected function assign(...$vars)
    {
        View::assign(...$vars);
    }

    /**
     * 解析和获取模板内容
     * @param string $template
     * @return string
     */
    protected function fetch(string $template = '') : string
    {
        return View::fetch($template);
    }

    /**
     * 重定向
     * @param mixed ...$args
     */
    protected function redirect(...$args)
    {
        throw new HttpResponseException(redirect(...$args));
    }

    /**
     * 方法不存在时执行
     * @param $name
     * @param $arguments
     * @return string|\think\response\Json
     */
    public function __call($name, $arguments)
    {
        if(Request::isAjax()) {
            return json(fail('抱歉，你访问的页面不存在！'));
        } else {
            return $this->fetch('statics/404.html');
        }
    }
}