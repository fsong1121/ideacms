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
namespace app\common\event;

use think\facade\Config;

class InitBase
{
    //行为扩展的执行入口必须是run
    public function handle()
    {
        //常量定义
        define('DS', DIRECTORY_SEPARATOR);
        //初始化模板配置
        $view_array = [
            // 视图输出字符串内容替换
            'tpl_replace_string'       => [
                '{__STATIC__}'  => '/statics',
                '{__TEMPLATE__}'  => '/template',
                '{__ADDONS__}'  => '/addons',
            ],
            //自定义标签库
            'taglib_pre_load' => 'app\common\taglib\Idea'
        ];
        Config::set($view_array, 'view');
    }
}