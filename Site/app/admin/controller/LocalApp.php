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

use app\common\logic\admin\LocalApp as LocalAppLogic;
use think\facade\Request;
use think\response\Json;

class LocalApp extends Base
{
    /**
     * 列表
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/local_app');
    }

    /**
     * 获取列表
     */
    public function getList() : Json
    {
        $param = Request::param();
        $perPage = Request::has('limit', 'get') ? $param['limit'] : $this->perPage;
        $keys = trimStr(Request::has('keys','get') ? $param['keys'] : '');
        $param['limit'] = $perPage;
        $param['keys'] = $keys;
        $logic = new LocalAppLogic();
        return json($logic->readList($param));
    }

    /**
     * 安装插件
     * @return Json
     */
    public function install() : Json
    {
        if($this->roleId == 0) {
            $param = Request::param();
            $name = $param['m_name'] ?? '';
            $logic = new LocalAppLogic();
            return json($logic->install($name));
        } else {
            return json(fail('权限不足，仅超级管理员可操作'));
        }
    }

    /**
     * 启用插件
     * @return Json
     */
    public function up() : Json
    {
        if($this->roleId == 0) {
            $param = Request::param();
            $name = $param['m_name'] ?? '';
            $logic = new LocalAppLogic();
            return json($logic->up($name));
        } else {
            return json(fail('权限不足，仅超级管理员可操作'));
        }
    }

    /**
     * 停用插件
     * @return Json
     */
    public function down() : Json
    {
        if($this->roleId == 0) {
            $param = Request::param();
            $name = $param['m_name'] ?? '';
            $logic = new LocalAppLogic();
            return json($logic->down($name));
        } else {
            return json(fail('权限不足，仅超级管理员可操作'));
        }
    }

    /**
     * 卸载插件
     * @return Json
     */
    public function uninstall() : Json
    {
        if($this->roleId == 0) {
            $param = Request::param();
            $name = $param['m_name'] ?? '';
            $logic = new LocalAppLogic();
            return json($logic->uninstall($name));
        } else {
            return json(fail('权限不足，仅超级管理员可操作'));
        }
    }
}