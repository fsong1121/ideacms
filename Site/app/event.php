<?php
// 事件定义文件
return [
    'bind'      => [
    ],

    'listen'    => [
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
        'AppInit'  => [
            'app\common\event\InitBase',
        ],
        'LoginLog' => [
            'app\common\event\LoginLog',
        ],
        'RegSuccess' => [
            'app\common\event\RegSuccess',
        ],
        'PaySuccess' => [
            'app\common\event\PaySuccess',
        ],
        'OrderLog' => [
            'app\common\event\OrderLog',
        ],
        'FinanceDetail' => [
            'app\common\event\FinanceDetail',
        ],
        'ReceiptOrder' => [
            'app\common\event\ReceiptOrder',
        ],
        'CancelOrder' => [
            'app\common\event\CancelOrder',
        ],
        'SendMessage' => [
            'app\common\event\SendMessage',
        ],
    ],

    'subscribe' => [
    ],
];
