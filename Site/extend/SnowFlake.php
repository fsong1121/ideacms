<?php

class SnowFlake
{
    const EPOCH = 1449928800000; //开始时间,固定一个小于当前时间的毫秒数
    const max12bit = 1024;
    const max41bit = 1099511627888;
    static $machineId = null; // 机器id

    public static function createOnlyId()
    {
        // 时间戳 42字节
        $time = floor(microtime(true) * 1000);
        // 当前时间 与 开始时间 差值
        $time -= self::EPOCH;
        // 二进制的 毫秒级时间戳
        $base = decbin(self::max41bit + $time);
        // 机器id10 字节
        if(!self::$machineId) {
            $machineid = self::$machineId;
        } else {
            $machineid = str_pad(decbin(self::$machineId),10,"0",STR_PAD_LEFT);
        }
        // 序列数 12字节
        $random = str_pad(decbin(mt_rand(0,self::max12bit)),12,"0",STR_PAD_LEFT);
        // 拼接
        $base = $base . $machineid . $random;
        // 转化为 十进制 返回
        return bindec($base);
    }
}