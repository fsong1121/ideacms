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
namespace app\index\controller;

use app\BaseController;
use think\exception\HttpResponseException;
use think\facade\Request;
use think\facade\View;

class Base extends BaseController
{
    protected string $uuid;
    protected int $uid;
    protected int $perPage;

    /**
     * 初始化
     */
    public function initialize()
    {
        $this->perPage = 20;
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