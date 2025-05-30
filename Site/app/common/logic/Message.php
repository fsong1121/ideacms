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
namespace app\common\logic;

use app\common\service\Wechat as WechatService;
use app\common\service\Sms as SmsService;
use think\facade\Db;

class Message extends BaseLogic
{
    /**
     * 发送消息
     * @param string $sn
     * @param string $type
     * @param string $state
     * @return array
     * @throws \think\api\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function send(string $sn = '',string $type = 'order',string $state = 'pay') : array
    {
        if($type == 'recharge') {
            //会员充值
            return success();
        } elseif ($type == 'vip') {
            //购买vip
            return success();
        } else {
            //订单
            $order = Db::name('order')
                ->where('order_sn',$sn)
                ->find();
            if(!empty($order)) {
                $wechat = new WechatService();
                $user = Db::name('user')->where('id',$order['user_id'])->find();
                //下单支付成功
                if($state == 'pay') {
                    //给用户发
                    if(config('message.sms_user_pay') == 1) {
                        //手机短信
                        $sms = new SmsService();
                        $sms->sendSms($order['tel'],config('message.sms_user_pay_id'),[$order['order_sn'],$order['pay_price']]);
                    }
                    if(config('message.gzh_user_pay') == 1 && !empty($user['wechat_openid'])) {
                        //公众号消息
                        $data = [];
                        $data['template_id'] = config('message.gzh_user_pay_id');
                        $data['touser'] = $user['wechat_openid'];
                        $data['data'] = [
                            'character_string2' => [
                                'value' => $order['order_sn']
                            ],
                            'amount5' => [
                                'value' => formatPrice($order['pay_price']) . '元'
                            ],
                            'thing6' => [
                                'value' => config('order.pay_type')[$order['pay_type']]
                            ],
                            'time26' => [
                                'value' => date("Y-m-d H:i:s",$order['add_date'])
                            ]
                        ];
                        $wechat->sendMsg($data);
                    }
                    if(config('message.miniapp_user_pay') == 1 && !empty($user['miniapp_openid'])) {
                        //小程序消息
                        $data = [];
                        $data['template_id'] = config('message.miniapp_user_pay_id');
                        $data['page'] = 'pages/order/list?state=2';
                        $data['touser'] = $user['miniapp_openid'];
                        $data['data'] = [
                            'character_string3' => [
                                'value' => $order['order_sn']
                            ],
                            'amount2' => [
                                'value' => formatPrice($order['pay_price']) . '元'
                            ],
                            'thing7' => [
                                'value' => config('order.pay_type')[$order['pay_type']]
                            ],
                            'time4' => [
                                'value' => date("Y-m-d H:i:s",$order['add_date'])
                            ]
                        ];
                        $wechat->sendMiniMsg($data);
                    }

                    //给商家发
                    if(config('message.sms_store_pay') == 1) {
                        //手机短信
                        $sms = new SmsService();
                        $sms->sendSms(config('site.tel'),config('message.sms_store_pay_id'),[$order['order_sn'],$order['pay_price']]);
                    }
                    if(config('message.gzh_store_pay') == 1 && !empty(config('site.openid'))) {
                        //公众号消息
                        $data['template_id'] = config('message.gzh_store_pay_id');
                        $data['touser'] = config('site.openid');
                        $data['data'] = [
                            'character_string4' => [
                                'value' => $order['order_sn']
                            ],
                            'amount1' => [
                                'value' => formatPrice($order['pay_price']) . '元'
                            ],
                            'phrase2' => [
                                'value' => config('order.pay_type')[$order['pay_type']]
                            ],
                            'time3' => [
                                'value' => date("Y-m-d H:i:s",$order['add_date'])
                            ]
                        ];
                        $wechat->sendMsg($data);
                    }
                }

                //发货成功
                if($state == 'send') {
                    //给用户发
                    if(config('message.sms_user_send') == 1) {
                        //手机短信
                        $sms = new SmsService();
                        $sms->sendSms($order['tel'],config('message.sms_user_send_id'),[$order['order_sn']]);
                    }
                    if(config('message.gzh_user_send') == 1 && !empty($user['wechat_openid'])) {
                        //公众号消息
                        $data = [];
                        $data['template_id'] = config('message.gzh_user_send_id');
                        $data['touser'] = $user['wechat_openid'];
                        $data['data'] = [
                            'character_string2' => [
                                'value' => $order['order_sn']
                            ],
                            'thing13' => [
                                'value' => $order['express_title']
                            ],
                            'character_string14' => [
                                'value' => $order['express_sn']
                            ],
                            'time12' => [
                                'value' => date("Y-m-d H:i:s",$order['add_date'])
                            ]
                        ];
                        $wechat->sendMsg($data);
                    }
                    //小程序订单需要自动发货使用自动发货
                    if($order['terminal'] == 4 && config('wechat.miniapp.auto_send') == 1) {
                        $data = [];
                        $itemDesc = '';
                        $logisticsType = 1;
                        //商品描述
                        $orderGoods = Db::name('order_goods')->where('order_id',$order['id'])->find();
                        if (!empty($orderGoods)) {
                            $goods = getGoodsInfo($orderGoods['goods_id']);
                            $itemDesc = $goods['title'] . "*" . $orderGoods['amount'];
                        }
                        $data['order_key'] = [
                            'order_number_type' => 2,
                            'transaction_id' => $order['pay_sn']
                        ];
                        if($order['express_type'] == 1) {
                            $expressCompany = '';
                            foreach (config('express.list') as $value) {
                                if($value['name'] == $order['express_title']) {
                                    $expressCompany = $value['code'];
                                }
                            }
                            $data['shipping_list'] = [
                                'tracking_no' => $order['express_sn'],
                                'express_company' => $expressCompany,
                                'item_desc' => $itemDesc
                            ];
                        } else {
                            if($order['send_type'] == 1) {
                                $logisticsType = 3;
                            } else {
                                $logisticsType = 4;
                            }
                            $data['shipping_list'] = [
                                'item_desc' => $itemDesc
                            ];
                        }
                        $data['logistics_type'] = $logisticsType; //1快递，2同城配送，3虚拟商品，4用户自提
                        $data['payer'] = [
                            'openid' => $user['miniapp_openid']
                        ];
                        $wechat->uploadShipping($data);
                    } else {
                        if(config('message.miniapp_user_send') == 1 && !empty($user['miniapp_openid'])) {
                            //小程序消息
                            $data = [];
                            //无需上传发货信息
                            $data['template_id'] = config('message.miniapp_user_send_id');
                            $data['page'] = 'pages/order/list?state=3';
                            $data['touser'] = $user['miniapp_openid'];
                            $data['data'] = [
                                'character_string7' => [
                                    'value' => $order['order_sn']
                                ],
                                'thing1' => [
                                    'value' => $order['express_title']
                                ],
                                'character_string2' => [
                                    'value' => $order['express_sn']
                                ],
                                'time3' => [
                                    'value' => date("Y-m-d H:i:s",$order['express_date'])
                                ]
                            ];
                            $wechat->sendMiniMsg($data);
                        }
                    }
                }
                return success();
            } else {
                return fail('订单不存在');
            }
        }
    }
}