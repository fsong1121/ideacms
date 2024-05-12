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

use app\common\logic\admin\Index as indexLogic;
use think\facade\Cache;
use think\response\Json;

class Index extends Base
{
    /**
     * 后台首页
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index() : string
    {
        $logic = new indexLogic();
        $adminMenu = $logic->getAdminMenu($this->uuid);
        $this->assign('adminMenu', $adminMenu);
        return $this->fetch('/index');
    }

    /**
     * 清除缓存
     * @return array
     */
    public function clearCache() : array
    {
        Cache::clear();
        return success();
    }

    /**
     * 获取菜单
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNavList() : Json
    {
        $logic = new indexLogic();
        $adminMenu = $logic->getAdminMenu($this->uuid);
        return json(success($adminMenu));
    }
}