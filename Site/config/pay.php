<?php
 return [
                'wechat_pay' => [
                    'open' => 0,
                    'mchid' => '',
                    'key' => '',
                    'apiclient_cert' => './statics/cert/apiclient_cert.pem',
                    'apiclient_key' => './statics/cert/apiclient_key.pem',
                    'notify_url' => '/api/v1.index.notify/weChatPay.html'
                ],
                'ali_pay' => [
                     'open' => 0,
                     'app_id' => '',
                     'public_key' => '',
                     'private_key' => '',
                     'notify_url' => '/api/v1.index.notify/alipay.html',
                     'return_url' => '/api/v1.index.notify/alipayReturn.html',
                 ],
                 'balance_pay' => 1,
                 'cod_pay' => 0
            ];