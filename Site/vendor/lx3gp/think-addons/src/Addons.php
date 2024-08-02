<?php
/**
 * +----------------------------------------------------------------------
 * | think-addons [thinkphp6]
 * +----------------------------------------------------------------------
 * | FILE: Addons.php
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

namespace think;

use think\App;
use think\helper\Str;
use think\facade\Config;
use think\facade\View;

abstract class Addons
{
    // app 容器
    protected $app;
    // 请求对象
    protected $request;
    // 当前插件标识
    protected $name;
    // 插件路径
    protected $addon_path;
    // 视图模型
    protected $view;
    // 插件配置
    protected $addon_config;
    // 插件信息
    protected $addon_info;

    /**
     * 插件构造函数
     * Addons constructor.
     * @param \think\App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $app->request;
        $this->name = $this->getName();
        $this->addon_path = $app->addons->getAddonsPath() . $this->name . DIRECTORY_SEPARATOR;
        $this->addon_config = "addon_{$this->name}_config";
        $this->addon_info = "addon_{$this->name}_info";
        $this->view = clone View::engine('Think');
        $this->view->config([
            'view_path' => $this->addon_path . 'view' . DIRECTORY_SEPARATOR
        ]);

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {}

    /**
     * 获取插件标识
     * @return mixed|null
     */
    final protected function getName()
    {
        $class = get_class($this);
        list(, $name, ) = explode('\\', $class);
        $this->request->addon = $name;

        return $name;
    }

    /**
     * 加载模板输出
     * @param string $template
     * @param array $vars           模板文件名
     * @return false|mixed|string   模板输出变量
     * @throws \think\Exception
     */
    protected function fetch($template = '', $vars = [])
    {
        return $this->view->fetch($template, $vars);
    }

    /**
     * 渲染内容输出
     * @access protected
     * @param  string $content 模板内容
     * @param  array  $vars    模板输出变量
     * @return mixed
     */
    protected function display($content = '', $vars = [])
    {
        return $this->view->display($content, $vars);
    }

    /**
     * 模板变量赋值
     * @access protected
     * @param  mixed $name  要显示的模板变量
     * @param  mixed $value 变量的值
     * @return $this
     */
    protected function assign($name, $value = '')
    {
        $this->view->assign([$name => $value]);

        return $this;
    }

    /**
     * 初始化模板引擎
     * @access protected
     * @param  array|string $engine 引擎参数
     * @return $this
     */
    protected function engine($engine)
    {
        $this->view->engine($engine);

        return $this;
    }

    /**
     * 获取配置信息
     * @param boolean $type 是否获取完整配置
     * @return array|mixed
     */
    final public function getConfig($type = false)
    {
        $config_file = $this->addon_path . 'config.php';
        $config = [];
        if (is_file($config_file)) {
            $config = (array) include $config_file;
        }
        if(!$type){
            return $config = isset($config['config']) ? $config['config'] : [];
        }
        return $config;
    }

    /**
     * 设置配置数据
     * @param       $name
     * @param array $value
     * @return array
     */
    final public function setConfig($name = '', $value = [])
    {
        try {
            if (empty($name)) {
                $name = $this->getName();
            }
            //获取配置文件的所有信息
            $config = $this->getConfig(true);
            $filePath = $this->addon_path . 'config.php';
            $code = "return [\n";
            $code = $code . "   'config' => [\n";
            foreach ($value as $k => $v) {
                $code = $code . "       '" . $k . "' => '" . $v . "',\n";
            }
            $code = $code . "   ]\n";
            if(isset($config['menu'])) {
                $code = $code . "   ,'menu' => [\n";
                foreach ($config['menu'] as $k => $v) {
                    if($k == 0) {
                        $code = $code . "       [\n";
                    } else {
                        $code = $code . "       ,[\n";
                    }
                    $code = $code . "           'title'=>'".$v['title']."',\n";
                    $code = $code . "           'url'=>'".$v['url']."',\n";
                    $code = $code . "           'other_operation'=>'".$v['other_operation']."',\n";
                    if(empty($v['childMenu'])) {
                        $code = $code . "           'childMenu'=>[]";
                    } else {
                        $code = $code . "           'childMenu'=>[\n";
                        foreach ($v['childMenu'] as $k1 => $v1) {
                            if($k1 == 0) {
                                $code = $code . "               [\n";
                            } else {
                                $code = $code . "               ,[\n";
                            }
                            $code = $code . "                   'title'=>'".$v1['title']."',\n";
                            $code = $code . "                   'url'=>'".$v1['url']."',\n";
                            $code = $code . "                   'other_operation'=>'".$v1['other_operation']."'\n";
                            $code = $code . "               ]\n";
                        }
                        $code = $code . "           ]\n";
                    }
                    $code = $code . "       ]\n";
                }
                $code = $code . "   ]\n";
            }
            $code = $code . "]";
            $code = "<?php\n " . $code . ";";
            file_put_contents($filePath, $code);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 插件基础信息
     * @return array
     */
    final public function getInfo()
    {
        // 文件属性
        $info = [];
        // 文件配置
        $info_file = $this->addon_path . 'addon.ini';
        if (!is_file($info_file) || !file_exists($info_file)) {         
            return [];
        }
        if (is_file($info_file) && file_exists($info_file)) {         
            $_info = parse_ini_file($info_file, true, INI_SCANNER_TYPED) ?: [];
            //$_info['url'] = (string) addons_url();
            $info = array_merge($_info, $info);
        }
        return isset($info) ? $info : [];
    }

    /**
     * 设置插件信息数据
     * @param       $name
     * @param array $value
     * @return array
     */
    final public function setInfo($name = '', $value = [])
    {
        if (empty($name)) {
            $name = $this->getName();
        }
        $info = $this->getInfo($name);
        $info = array_merge($info, $value);
        return $this->writeConfigAndIniInFile($name, $info, 'ini');
    }


    /**
     * 获取菜单信息
     * @param boolean $type 是否获取完整配置
     * @return array|mixed
     */
    final public function getMenu($type = false)
    {
        $configFile = $this->addon_path . 'config.php';
        if (is_file($configFile)) {
            $config = (array) include $configFile;
        }
        return isset($config['menu']) ? $config['menu'] : [];
    }

    /**
     * 设置菜单数据
     * @param       $name
     * @param array $value
     * @return array
     */
    final public function setMenu($name = '', $value = [])
    {
        if (empty($name)) {
            $name = $this->getName();
        }
        $config = $this->getConfig(true);
        $config['menu'] =  $value;
        return $this->writeConfigAndIniInFile($name, $config);
    }


    /**
     * 设置配置文件
     * @param string|null $name
     * @param array|null $value
     * @param string $type
     * @return bool
     */
    private function writeConfigAndIniInFile(string $name = null, array $value = null, string $type='config')
    {
        if (!$name || !$value) {
            return false;
        }
        //  根据类型组装文件地址  
        if ($type == "ini") {
            $filePath = $this->addon_path . 'addon.ini';
            $content = $this->listINIRecursive($value);
        }

        //  判断目录是否存在

        if (!is_file($filePath) || !file_exists($filePath) ) {
            @touch($filePath);
        }

        //  写入文件并返回写入动作的结果
        if(file_put_contents($filePath, $content)){
            return true;
        }
        return false;
    }

    /**
     * 数组转INI配置数据
     * @param       $name
     * @param array $value
     * @return array
     */
    private function listINIRecursive($array, $indent = 0)
    {
        global $str;
        foreach ($array as $k => $v)
        {
            if (is_array($v))
            {
                $str.= "[$k]" . PHP_EOL;
                $this->listINIRecursive($v, $indent + 1);
            }else{
                if($v !== ""){ $str.= "$k = $v" . PHP_EOL;}
            }
        }
        return $str;
    }

    //必须实现安装
    //abstract public function install();

    //必须卸载插件方法
    //abstract public function uninstall();
}
