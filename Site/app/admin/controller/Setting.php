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

use app\common\logic\admin\Setting as SettingLogic;
use think\facade\Request;
use think\response\Json;

class Setting extends Base
{
    /**
     * 主题设置
     * @return string
     */
    public function theme() : string
    {
        $this->assign('setting', config('setting'));
        return $this->fetch('/setting_theme');
    }

    /**
     * 保存主题设置
     */
    public function saveTheme() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $logic = new SettingLogic();
            $data = $logic->saveTheme($param);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 手机端设置
     * @return string
     */
    public function mobile() : string
    {
        $this->assign('setting', config('setting'));
        return $this->fetch('/setting_mobile');
    }

    /**
     * 保存手机端设置
     */
    public function saveMobile() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $configLogic = new SettingLogic();
            $data = $configLogic->saveMobile($param);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

}