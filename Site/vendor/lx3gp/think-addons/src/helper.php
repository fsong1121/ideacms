<?php
/**
 * +----------------------------------------------------------------------
 * | think-addons [ThinkPHP6]
 * +----------------------------------------------------------------------
 * | FILE: helper.php
 * | AUTHOR: DreamLee
 * | EMAIL: 1755773846@qq.com
 * | QQ: 1755773846
 * | DATETIME: 2022/03/07 14:47
 * |-----------------------------------------
 * | 不积跬步,无以至千里；不积小流，无以成江海！
 * +----------------------------------------------------------------------
 * | Copyright (c) 2022 DreamLee All rights reserved.
 * +----------------------------------------------------------------------
 */
declare(strict_types=1);


use think\facade\Db;
use think\facade\Event;
use think\facade\Route;
use think\helper\{
    Str, Arr
};

\think\Console::starting(function (\think\Console $console) {
    $console->addCommands([
        'addons:config' => '\\think\\addons\\command\\SendConfig'
    ]);
});

// 插件类库自动载入
spl_autoload_register(function ($class) {
    $class = ltrim($class, '\\');
    $dir = app()->getRootPath();
    $namespace = 'addons';
    if (strpos($class, $namespace) === 0) {
        $class = substr($class, strlen($namespace));
        $path = '';
        if (($pos = strripos($class, '\\')) !== false) {
            $path = str_replace('\\', '/', substr($class, 0, $pos)) . '/';
            $class = substr($class, $pos + 1);
        }
        $path .= str_replace('_', '/', $class) . '.php';
        $dir .= $namespace . $path;
        if (file_exists($dir)) {
            include $dir;
            return true;
        }
        return false;
    }
    return false;
});

if (!function_exists('hook')) {
    /**
     * 处理插件钩子
     * @param string $event 钩子名称
     * @param array|null $params 传入参数
     * @param bool $once 是否只返回一个结果
     * @return mixed
     */
    function hook($event, $params = null, bool $once = false)
    {
        $result = Event::trigger($event, $params, $once);

        return join('', $result);
    }
}

if (!function_exists('get_addons_instance')) {
    /**
     * 获取插件的单例
     * @param string $name 插件名
     * @return mixed|null
     */
    function get_addons_instance($name)
    {
        static $_addons = [];
        if (isset($_addons[$name])) {
            return $_addons[$name];
        }
        $class = get_addons_class($name);
        if (class_exists($class)) {
            $_addons[$name] = new $class(app());
            return $_addons[$name];
        } else {
            return null;
        }
    }
}

if (!function_exists('check_addons_info')) {
    /**
     * 检查配置信息是否完整
     * @return bool
     */
    function check_addons_info(array $addon_info=null)
    {
        $info_check_keys = ['name', 'title', 'description', 'status', 'source', 'isInstall', 'author',  'version'];
        foreach ($info_check_keys as $value) {
            if (!$addon_info || !array_key_exists($value, $addon_info)) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('get_addons_info')) {
    /**
     * 读取插件的基础信息
     * @param string $name 插件名
     * @return array
     */
    function get_addons_info($name)
    {
        $addon = get_addons_instance($name);
        if (!$addon) {
            return [];
        }
        return $addon->getInfo();
    }
}

if (!function_exists('set_addons_info')) {
    /**
     * 设置基础配置信息
     * @param string $name  插件名
     * @param array  $array 配置数据
     * @return boolean
     * @throws Exception
     */
    function set_addons_info($name, $array)
    {
        $addon = get_addons_instance($name);
        if (!isset($array['name']) || !isset($array['title']) || !isset($array['version']) || !isset($array['isInstall'])) {
            return false;
        }
        return $addon->setInfo($name, $array);
    }
}

if (!function_exists('get_addons_class')) {
    /**
     * 获取插件类的类名
     * @param string $name 插件名
     * @param string $type 返回命名空间类型
     * @param string $class 当前类名
     * @return string
     */
    function get_addons_class($name, $type = 'hook', $class = null)
    {
        $name = trim($name);
        // 处理多级控制器情况
        if (!is_null($class) && strpos($class, '.')) {
            $class = explode('.', $class);

            $class[count($class) - 1] = Str::studly(end($class));
            $class = implode('\\', $class);
        } else {
            $class = Str::studly(is_null($class) ? $name : $class);
        }
        switch ($type) {
            case 'controller':
                $namespace = '\\addons\\' . $name . '\\controller\\' . $class;
                break;
            default:
                $namespace = '\\addons\\' . $name . '\\Plugin';
        }
        return class_exists($namespace) ? $namespace : '';
    }
}

if (!function_exists('get_addons_list')) {
    /**
     * 获取所有插件列表
     * @return array|null
     */
    function get_addons_list() {
        $addonsPath = root_path() . "addons";
        $addons = scandir($addonsPath);
        $_addons_list= [];
        foreach($addons as $name){
            if (is_dir($addonsPath . DIRECTORY_SEPARATOR . $name)) {
                //  判断插件的有效性
                //  获取单例插件的插件详情, 当结果为null时，表示不是插件
                if(!get_addons_instance($name)) {
                    continue;
                }
                //  获取插件信息以及配置信息
                $addon_info = get_addons_instance($name)->getInfo();
                //  判断单例插件的参数是否完整
                if (empty($addon_info) || !check_addons_info($addon_info)) {
                    continue;
                }
                $_addons_list[] = $addon_info;
            }
        }
        return array_values($_addons_list);
    }
}

if (!function_exists('addons_url')) {
    /**
     * 插件显示内容里生成访问插件的url
     * @param $url
     * @param array $param
     * @param bool|string $suffix 生成的URL后缀
     * @param bool|string $domain 域名
     * @return bool|string
     */
    function addons_url($url = '', $param = [], $suffix = true, $domain = false)
    {
        $request = app('request');
        if (empty($url)) {
            // 生成 url 模板变量
            $addons = $request->addon;
            $controller = $request->controller();
            $controller = str_replace('/', '.', $controller);
            $action = $request->action();
        } else {
            $url = Str::studly($url);
            $url = parse_url($url);
            if (isset($url['scheme'])) {
                $addons = strtolower($url['scheme']);
                $controller = $url['host'];
                $action = trim($url['path'], '/');
            } else {
                $route = explode('/', $url['path']);
                $addons = $request->addon;
                $action = array_pop($route);
                $controller = array_pop($route) ?: $request->controller();
            }
            $controller = Str::snake((string)$controller);

            /* 解析URL带的参数 */
            if (isset($url['query'])) {
                parse_str($url['query'], $query);
                $param = array_merge($query, $param);
            }
        }
        return Route::buildUrl("@addons/{$addons}/{$controller}/{$action}", $param)->suffix($suffix)->domain($domain);
    }
}

if(!function_exists('get_addons_config')){
    /**
     * 获取插件类的配置值值
     * @param string $name 插件名
     * @return array
     */
    function get_addons_config($name)
    {
        $addon = get_addons_instance($name);
        if (!$addon) {
            return [];
        }
        return $addon->getConfig();
    }
}

if(!function_exists('set_addons_config')){
    /**
     * 写入配置文件
     * @param string  $name     插件名
     * @param array   $config   配置数据
     * @return bool
     * @throws Exception
     */
    function set_addons_config(string $name = null, array $config = null)
    {
        if(!$name || !$config){
            return false;
        }
        $addon = get_addons_instance($name);
        if ($addon->setConfig($name, $config)) {
            // 写入配置文件
            return true;
        }
        return false;
    }
}

if(!function_exists('get_addons_menu')){
    /**
     * 获取插件类的配置值值
     * @param string $name 插件名
     * @return array
     */
    function get_addons_menu($name)
    {
        $addon = get_addons_instance($name);
        if (!$addon) {
            return [];
        }
        return $addon->getMenu($name);
    }
}

if(!function_exists('set_addons_menu')) {
    /**
     * 写入配置文件
     * @param string  $name     插件名
     * @param array   $config   配置数据
     * @param boolean $type     是否写入配置文件
     * @return bool
     * @throws Exception
     */
    function set_addons_menu(string $name = null, array $config = null)
    {
        if(!$name || !$config){
            return false;
        }
        $addon = get_addons_instance($name);
        if ($addon->setMenu($name, $config)) {
            // 写入配置文件
            return true;
        }
        return false;
    }
}

//  删除数据表
if (!function_exists("drop_table")) {
    function drop_table (string $name=null) {
        //  判断插件名称是否存在
        if(!$name) { return false; }
        //  删除数据表
        try{
            $dataTableList = get_addons_tables($name);  //  获取message插件所有的数据表，主要是根据安装文件来获取的
            if(!empty($dataTableList)) {
                $sql = "DROP TABLE IF EXISTS ";
                foreach ($dataTableList as $dtl) {
                    $sql .= "`{$dtl}`,";
                }
                $sql = substr($sql, 0, -1) . ";";
                $sql_status = Db::execute($sql);
                if($sql_status === false) {
                    return false;
                }
            }
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
}

if(!function_exists('get_addons_tables')){
    /**
     * 获取插件创建的表
     * @param string $name 插件名
     * @return array
     */
    function get_addons_tables($addonsName=null, $sqlFileName='install')
    {
        if (!$addonsName) {
            return [];
        }
        $addonInfo = get_addons_info($addonsName);
        if (!$addonInfo) {
            return [];
        }
        $regex = "/^CREATE\s+TABLE\s+(IF\s+NOT\s+EXISTS\s+)?`?([a-zA-Z_]+)`?/mi";
        $sqlFile = root_path() . "addons" . DIRECTORY_SEPARATOR . $addonsName . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . $sqlFileName . '.sql';
        $tables = [];
        if (is_file($sqlFile)) {
            preg_match_all($regex, file_get_contents($sqlFile), $matches);
            if ($matches && isset($matches[2]) && $matches[2]) {
                $prefix = config('database.prefix');
                $tables = array_map(function ($item) use ($prefix) {
                    return str_replace("{PREFIX}", $prefix, $item);
                }, $matches[2]);
            }
        }
        return $tables;
    }
}