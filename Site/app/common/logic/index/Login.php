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
namespace app\common\logic\index;

use app\common\service\JwtAuth as AuthService;
use app\common\service\Wechat as WechatService;
use app\common\logic\BaseLogic;
use think\facade\Cache;
use think\facade\Event;
use think\facade\Db;
use think\api\Client;

class Login extends BaseLogic
{
    /**
     * 账号登录
     * @param array $param
     * @return array
     */
    public function login(array $param) : array
    {
        try {
            if(empty($param['m_uid']) || empty($param['m_pwd'])) {
                return fail('账号或密码为空');
            }
            $user = Db::name('user')
                ->where('uid', $param['m_uid'])
                ->where('pwd',makePassword($param['m_pwd']))
                ->find();
            if (!empty($user)) {
                return $this->setLogin($user['id']);
            } else {
                return fail('用户账号或密码错误');
            }
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 发送验证码
     * @param array $param
     * @return mixed|string
     * @throws \think\api\Exception
     */
    public function sendSmsCode(array $param)
    {
        $code = makeRandStr(4);
        Cache::set('smsCode' . $param['m_tel'], $code, 300);
        $client = new Client(config('sms.appCode'));
        return $client->smsSend()
            ->withSignId(config('sms.signId'))
            ->withTemplateId(config('sms.codeTemplateId'))
            ->withPhone($param['m_tel'])
            ->withParams('{"code": "'.$code.'"}')
            ->request();
    }

    /**
     * 手机验证码登录
     * @param array $param
     * @return array
     */
    public function smsCodeLogin(array $param) : array
    {
        try {
            if(empty($param['m_tel']) || empty($param['m_code'])) {
                return fail('手机号或验证码为空');
            }
            if($param['m_code'] != Cache::get('smsCode' . $param['m_tel'])) {
                return fail('手机验证码不正确');
            }
            $user = Db::name('user')
                ->where('uid',$param['m_tel'])
                ->find();
            if(!empty($user)) {
                if ($user['is_work'] == 0) {
                    return fail('此用户已被锁定');
                } else {
                    return $this->setLogin($user['id']);
                }
            } else {
                //添加会员
                $pid = $param['pid'];
                $parentUser = Db::name('user')->where('id', $pid)->find();
                if (!empty($parentUser) && $parentUser['is_fx'] == 0) {
                    $pid = 0;
                }
                $userId = Db::name('user')->insertGetId([
                    'uuid' => makeUuid(),
                    'uid' => $param['m_tel'],
                    'mobile' => $param['m_tel'],
                    'pid' => $pid,
                    'is_work' => 1,
                    'add_date' => time()
                ]);
                //注册成功后事件
                Event::trigger('RegSuccess',['user_id' => $userId]);
                return $this->setLogin($userId);
            }
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 小程序登录
     * @param array $param
     * @return array
     */
    public function miniappLogin(array $param) : array
    {
        try {
            $appId = config('wechat.miniapp.appid');
            $appSecret = config('wechat.miniapp.appsecret');
            if(isset($param['loginCode']) && !empty($param['loginCode'])) {
                //获取手机号
                $phone = '';
                $wechat = new WechatService();
                if(!empty($param['phoneCode'])) {
                    $accessToken = $wechat->getAccessToken('miniapp','login');
                    $phoneUrl = 'https://api.weixin.qq.com/wxa/business/getuserphonenumber?access_token=' . $accessToken;
                    $data = curlPost($phoneUrl,['code'=>$param['phoneCode']]);
                    if($data['errcode'] == 0) {
                        $phone = $data['phone_info']['purePhoneNumber'];
                    } else {
                        return fail($data['errmsg']);
                    }
                }
                //添加会员
                $pid = $param['pid'];
                $parentUser = Db::name('user')->where('id',$pid)->find();
                if(empty($parentUser) || $parentUser['is_fx'] == 0) {
                    $pid = 0;
                }
                $curl_url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appId.'&secret='.$appSecret.'&js_code='.$param['loginCode'].'&grant_type=authorization_code';
                $data = curlGet($curl_url);
                if(isset($data['openid'])) {
                    $unionid = $data['unionid'] ?? '';
                    $miniappUser = Db::name('user')->where('miniapp_openid',$data['openid'])->find();
                    if(empty($miniappUser)) {
                        //新会员
                        try {
                            $user = empty($unionid) ? [] : Db::name('user')->where('wechat_unionid',$unionid)->find();
                            if(!empty($user)) {
                                //微信其他端登录过
                                Db::name('user')
                                    ->where('wechat_unionid',$unionid)
                                    ->update(['miniapp_openid'=>$data['openid']]);
                                $userId = $user['id'];
                            } else {
                                //如果手机号登录过就只更新
                                $user = Db::name('user')->where('uid',$phone)->find();
                                if(!empty($user)) {
                                    Db::name('user')
                                        ->where('uid',$phone)
                                        ->update(['miniapp_openid'=>$data['openid'],'wechat_unionid'=>$unionid]);
                                    $userId = $user['id'];
                                } else {
                                    $userId = Db::name('user')->insertGetId([
                                        'uuid' => makeUuid(),
                                        'uid' => $phone,
                                        'mobile' => $phone,
                                        'pid' => $pid,
                                        'miniapp_openid' => $data['openid'],
                                        'wechat_unionid' => $unionid,
                                        'is_work' => 1,
                                        'add_date' => time()
                                    ]);
                                }
                            }
                            //注册成功后事件
                            Event::trigger('RegSuccess',['user_id' => $userId]);
                            return $this->setLogin($userId);
                        } catch (\Exception $e) {
                            return fail($e->getMessage());
                        }
                    } else {
                        //老会员
                        if ($miniappUser['is_work'] == 0) {
                            return fail('此用户已被锁定');
                        } else {
                            return $this->setLogin($miniappUser['id']);
                        }
                    }
                } else {
                    return fail($data['errmsg']);
                }
            } else {
                return fail('code为空或不存在');
            }
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 退出登录
     * @param string $token
     * @return array
     */
    public function loginOut(string $token = '') : array
    {
        $auth = new AuthService();
        return $auth->loginOut($token);
    }

    /**
     * 判断是否登录
     * @param array $param
     * @return array
     */
    public function checkLogin(array $param) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $user = Db::name('user')
                ->where('id',$param['user_id'])
                ->where('is_work',1)
                ->find();
            if(empty($user)) {
                $res = fail('会员不存在或已被锁定');
            } else {
                $res['data']['user_id'] = $user['id'];
            }
            return $res;
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 获取微信小程序openid
     * @param array $param
     * @return array
     */
    public function getOpenId(array $param) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $appId = config('wechat.miniapp.appid');
            $appSecret = config('wechat.miniapp.appsecret');
            if(isset($param['loginCode'])) {
                $curl_url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appId.'&secret='.$appSecret.'&js_code='.$param['loginCode'].'&grant_type=authorization_code';
                $data = curlGet($curl_url);
                if(isset($data['openid'])) {
                    $res['openid'] = $data['openid'];
                    return $res;
                } else {
                    return fail($data['errmsg']);
                }
            } else {
                return fail('code为空或不存在');
            }
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 登录后获取token
     * @param int $user_id
     * @return array
     */
    private function setLogin(int $user_id) : array
    {
        $auth = new AuthService();
        $res = $auth->login(['id'=>$user_id,'type'=>'user']);
        if($res['code'] == 0) {
            header('token:' . $res['access_token']);
        }
        return $res;
    }

}