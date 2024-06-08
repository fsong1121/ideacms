<?php
/**
 * +----------------------------------------------------------------------
 * | think-addons [thinkphp6]
 * +----------------------------------------------------------------------
 * | FILE: SendConfig.php
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

namespace think\addons\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Env;

class SendConfig extends Command
{

    public function configure()
    {
        $this->setName('addons:config')
             ->setDescription('send config to config folder');
    }

    public function execute(Input $input, Output $output)
    {
        //获取默认配置文件
        $content = file_get_contents(root_path() . 'vendor/lx3gp/think-addons/src/config.php');

        $configPath = config_path() . '/';
        $configFile = $configPath . 'addons.php';


        //判断目录是否存在
        if (!file_exists($configPath)) {
            mkdir($configPath, 0755, true);
        }

        //判断文件是否存在
        if (is_file($configFile)) {
            throw new \InvalidArgumentException(sprintf('The config file "%s" already exists', $configFile));
        }

        if (false === file_put_contents($configFile, $content)) {
            throw new \RuntimeException(sprintf('The config file "%s" could not be written to "%s"', $configFile,$configPath));
        }

        $output->writeln('create addons config ok');
    }

}