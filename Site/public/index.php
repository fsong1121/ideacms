<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

//防止iframe框架攻击
header('X-Frame-Options: SAMEORIGIN');
//允许前端跨域请求
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:GET, POST');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Expose-Headers:*');
//不缓存页面
header('Cache-Control:no-cache,must-revalidate,no-store');
header('Pragma:no-cache');
header('Expires:-1');

// 检测PHP环境
if(version_compare(PHP_VERSION,'8.0.0','<'))
{
    header("Content-type: text/html; charset=utf-8");
    die('PHP 版本必须 >= 8.0!');
}

//检测是否安装
if (!file_exists("install.lock") && !strpos($_SERVER['REQUEST_URI'], 'install')) {
    header('Location:/install/index/index.html');
    exit();
}

require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
