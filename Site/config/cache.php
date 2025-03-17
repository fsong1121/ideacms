<?php

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------

return [
    // 默认缓存驱动
    'default' => env('CACHE_TYPE', 'file'),

    // 缓存连接方式配置
    'stores'  => [
        'file' => [
            // 驱动方式
            'type'       => 'File',
            // 缓存保存目录
            'path'       => '',
            // 缓存前缀
            'prefix'     => '',
            // 缓存有效期 0表示永久缓存
            'expire'     => 0,
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => [],
        ],
        'redis' => [
            'type'     => 'redis',     // 驱动类型
            'host'     => env('REDIS_HOST', '127.0.0.1'), // Redis服务器IP
            'port'     => env('REDIS_PORT', '6379'),        // 端口
            'password' => env('REDIS_PASS', ''),          // 密码（若无密码则留空）
            'select'   => env('REDIS_SELECT', '1'),           // 默认数据库编号
            'prefix'   => env('REDIS_PRE', ''),          // 缓存前缀（可选）
        ],
        // 更多的缓存连接
    ],
];
