<?php
 return [
     //订单状态
     'order_state' => [
         '-1'=>'已取消',
         '1'=>'待付款',
         '2'=>'待发货',
         '3'=>'待收货',
         '4'=>'已完成'
     ],
     //订单类型
     'order_type' => [
         ''=>'普通订单',
         'integral'=>'积分订单',
         'activity'=>'促销订单',
         'seckill'=>'秒杀订单',
         'assist'=>'助力订单',
         'combination'=>'拼团订单',
         'raffle'=>'抽奖订单'
     ],
     //支付方式
     'pay_type' => [
         '0'=>'未支付',
         '1'=>'微信支付',
         '2'=>'支付宝',
         '3'=>'网银支付',
         '4'=>'余额支付',
         '5'=>'货到付款'
     ],
     //配送方式
     'send_type' => [
         '1'=>'快递',
         '2'=>'自提'
     ],
     //订单来源
     'terminal' => [
         '1'=>'H5端',
         '2'=>'PC端',
         '3'=>'微信端',
         '4'=>'小程序端',
         '5'=>'APP端'
     ],
     //退款类型
     'refund_type' => [
         '1'=>'仅退款',
         '2'=>'退货退款',
         '3'=>'仅换货'
     ],
     //退款状态
     'refund_state' => [
         '1'=>'部分退款',
         '2'=>'全部退款'
     ],
     //资金流水类型
     'finance_type' => [
         '1'=>'订单支付',
         '2'=>'订单退款',
         '3'=>'会员充值',
         '4'=>'充值退款',
         '5'=>'佣金提现',
         '6'=>'购买vip'
     ]
 ];