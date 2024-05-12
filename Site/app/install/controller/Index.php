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
namespace app\install\controller;

use think\facade\Config;
use think\facade\Request;
use think\facade\Cache;
use think\response\Json;
use think\facade\Db;

class Index extends Base
{
    /**
     * 安装向导
     * @return string
     */
    public function index() : string
    {
        $param = Request::param();
        $step = $param['step'] ?? 1;
        $step = intval($step);
        if($step < 1 || $step > 4) {
            $step = 1;
        }
        $msg = '';
        //运行环境
        $envArr = [];
        $envArr['php'] = PHP_VERSION;
        $envArr['mysqli'] = extension_loaded('mysqli') ? 1 : 0;
        $envArr['redis'] = extension_loaded('redis') ? 1 : 0;
        $envArr['curl'] = extension_loaded('curl') ? 1 : 0;
        $envArr['fileinfo'] = extension_loaded('fileinfo') ? 1 : 0;
        $envArr['gd'] = extension_loaded('gd') ? 1 : 0;
        if(floatval($envArr['php']) < 8.0) {
            $msg = 'php版本需大于8.0';
        }
        if(empty($msg) && $envArr['mysqli'] == 0) {
            $msg = '请开启mysqli模块';
        }
        if(empty($msg) && $envArr['redis'] == 0) {
            //$msg = '请开启redis模块';
        }
        if(empty($msg) && $envArr['curl'] == 0) {
            $msg = '请开启curl模块';
        }
        if(empty($msg) && $envArr['fileinfo'] == 0) {
            $msg = '请开启fileinfo模块';
        }
        if(empty($msg) && $envArr['gd'] == 0) {
            $msg = '请开启gd模块';
        }
        //目录权限
        $dirArr = [];
        $dirArr[0] = is_writable(root_path()) && is_readable(root_path()) ? 1 : 0;
        $dirArr[1] = is_writable(root_path() . 'config/') && is_readable(root_path() . 'config/') ? 1 : 0;
        $dirArr[2] = is_writable(root_path() . 'runtime/') && is_readable(root_path() . 'runtime/') ? 1 : 0;
        $dirArr[3] = is_writable(root_path() . 'public/') && is_readable(root_path() . 'public/') ? 1 : 0;
        $dirArr[4] = is_writable(root_path() . 'addons/') && is_readable(root_path() . 'addons/') ? 1 : 0;
        if(empty($msg) && $dirArr[0] == 0) {
            $msg = '根目录权限不足';
        }
        if(empty($msg) && $dirArr[1] == 0) {
            $msg = 'config目录权限不足';
        }
        if(empty($msg) && $dirArr[2] == 0) {
            $msg = 'runtime目录权限不足';
        }
        if(empty($msg) && $dirArr[3] == 0) {
            $msg = 'public目录权限不足';
        }
        if(empty($msg) && $dirArr[4] == 0) {
            $msg = 'addons目录权限不足';
        }

        Cache::set('installMsg',$msg,3600);
        $this->assign('step', $step);
        $this->assign('envArr', $envArr);
        $this->assign('dirArr', $dirArr);
        $this->assign('msg', $msg);

        return $this->fetch('/index');
    }

    /**
     * 安装
     * @return Json
     */
    public function install() : Json
    {
        Cache::set('installErr','',3600);
        if(file_exists("install.lock")) {
            Cache::set('installErr','已安装，如需重装请删除public/install.lock文件',3600);
            return json(fail('已安装，如需重装请删除public/install.lock文件'));
        }

        $msg = Cache::get('installMsg','');
        if(!empty($msg)) {
            Cache::set('installErr',$msg,3600);
            return json(fail($msg));
        }

        $param = Request::param();
        $adminUser = $param['admin_user'] ?? 'admin';
        $adminPass = $param['admin_pass'] ?? 'admin123';
        $version = 'v1.0.1';

        //生成配置文件
        $env = public_path() . 'statics/install/install.env';
        $parse = parse_ini_file($env, true);
        $parse['APP_DEBUG'] = 'false';
        $parse['DB_HOST'] = $param['db_host'] ?? '127.0.0.1';
        $parse['DB_NAME'] = $param['db_name'] ?? 'haopu';
        $parse['DB_USER'] = $param['db_user'] ?? 'root';
        $parse['DB_PASS'] = $param['db_pass'] ?? '123456';
        $parse['DB_PORT'] = $param['db_port'] ?? '3306';
        $parse['DB_PREFIX'] = $param['db_prefix'] ?? 'idea_';
        $content = parseArrayIni($parse);
        file_put_contents(root_path() . '.env', $content);

        // 读取MySQL数据
        $path = public_path() . 'statics/install/install.sql';
        $sql = file_get_contents($path);
        $sql = str_replace("\r", "\n", $sql);
        $sql = str_replace("{PREFIX}", $parse['DB_PREFIX'], $sql);
        $total = substr_count($sql,'CREATE') + 1;
        $sql = explode(";\n", $sql);
        Cache::set('installTotal',$total,3600);

        // 链接数据库
        try {
            $connect = mysqli_connect($parse['DB_HOST'],$parse['DB_USER'],$parse['DB_PASS'],$parse['DB_NAME'],$parse['DB_PORT']);
        } catch (\Throwable $e) {
            Cache::set('installErr','连接数据库错误',3600);
            return json(fail('连接数据库错误'));
        }

        // 建表
        $logs = [];
        $nums = 0;
        try {
            // 写入数据库
            foreach ($sql as $key => $value) {
                cache('progress', $key, 3600);
                $value = trim($value);
                if (empty($value)) {
                    continue;
                }
                if (substr_count($value,'CREATE') > 0) {
                    $name = preg_replace("/^CREATE TABLE `(\w+)` .*/s", "\\1", $value);
                    $msg = "创建数据表 {$name} ";
                    if (false !== mysqli_query($connect, $value)) {
                        $msg .= '成功！';
                        $logs[$nums] = [
                            'id' => $nums,
                            'msg' => $msg,
                        ];
                        $nums++;
                        Cache::set('installNums',$nums,3600);
                        Cache::set('installLogs',$logs,3600);
                    }
                } else {
                    mysqli_query($connect, $value);
                }
            }
        } catch (\Throwable $e) { // 异常信息
            Cache::set('installErr',$e->getMessage(),3600);
            return json(fail($e->getMessage()));
        }

        // 初始化管理员账户
        $logs[$nums] = [
            'id' => $nums,
            'msg' => '初始化数据中...',
        ];
        Cache::set('installNums',$nums+1,3600);
        Cache::set('installLogs',$logs,3600);
        file_put_contents(public_path() . 'install.lock', parseArrayIni(['version'=>$version,'date'=>time()]));

        // 获取当前数据库配置信息
        $config = Config::get('database');
        // 修改要切换的数据库名称或其他参数
        $config['connections']['mysql']['hostname'] = $parse['DB_HOST'];
        $config['connections']['mysql']['database'] = $parse['DB_NAME'];
        $config['connections']['mysql']['username'] = $parse['DB_USER'];
        $config['connections']['mysql']['password'] = $parse['DB_PASS'];
        $config['connections']['mysql']['hostport'] = $parse['DB_PORT'];
        $config['connections']['mysql']['prefix'] = $parse['DB_PREFIX'];
        // 保存修改后的数据库配置
        Config::set($config, 'database');
        // 断开与原始数据库的连接
        Db::connect()->close();
        // 重新连接到新的数据库
        Db::connect();
        // 添加管理员
        Db::table($parse['DB_PREFIX'] . 'admin')->insert([
            'uuid' => makeUuid(),
            'uid' => $adminUser,
            'pwd' => makePassword($adminPass),
            'is_work' => 1,
            'add_date' => time()
        ]);
        // 分批录入地址库，每次最多800条数据
        $areaData = json_decode(file_get_contents(public_path() . 'statics/install/area.txt'),true);
        Db::table($parse['DB_PREFIX'] . 'area')
            ->limit(800)
            ->insertAll($areaData);
        return json(success());
    }

    /**
     * 获取安装进度
     * @return Json
     */
    public function progress() : Json
    {
        // 查询错误
        $error = Cache::get('installErr','');
        if (!empty($error)) {
            return json(fail($error));
        }
        // 获取任务信息
        $logs = Cache::get('installLogs',['id' => 9999,'msg' => '获取任务信息失败！']);
        $progress = sprintf("%.2f",Cache::get('installNums',0) / Cache::get('installTotal',47)) * 100;
        $result = [
            'code' => 0,
            'data' => $logs,
            'progress' => $progress,
        ];
        return json($result);
    }
}