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

use app\common\service\Auth as AuthService;
use app\common\service\JwtAuth as JwtAuthService;
use app\common\service\Wechat as WechatService;
use app\common\service\Sms as SmsService;
use app\common\logic\BaseLogic;
use think\facade\Cache;
use think\facade\Cookie;
use think\facade\Event;
use think\facade\Db;

class Login extends BaseLogic
{
    /**
     * 账号注册
     * @param array $param
     * @return array
     */
    public function register(array $param) : array
    {
        try {
            if(empty($param['m_uid']) || empty($param['m_pwd'])) {
                return fail('账号或密码为空');
            }
            if(isset($param['m_captcha'])) {
                if(!captcha_check($param['m_captcha'])){
                    return fail('验证码错误');
                }
            }
            $user = Db::name('user')
                ->where('uid', $param['m_uid'])
                ->find();
            if (empty($user)) {
                //添加会员
                $pid = $param['pid'];
                $parentUser = Db::name('user')->where('id', $pid)->find();
                if (!empty($parentUser) && $parentUser['is_fx'] == 0) {
                    $pid = 0;
                }
                $data = [
                    'uuid' => makeUuid(),
                    'uid' => $param['m_uid'],
                    'pwd' => makePassword($param['m_pwd']),
                    'pid' => $pid,
                    'is_work' => 1,
                    'add_date' => time()
                ];
                $userId = Db::name('user')->insertGetId($data);
                //注册成功后事件
                Event::trigger('RegSuccess',['user_id' => $userId]);
                return $this->setLogin($userId);
            } else {
                return fail('此账号已存在');
            }
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

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
            if(isset($param['m_captcha'])) {
                if(!captcha_check($param['m_captcha'])){
                    return fail('验证码错误');
                }
            }
            $user = Db::name('user')
                ->where('uid', $param['m_uid'])
                ->where('pwd',makePassword($param['m_pwd']))
                ->find();
            if (!empty($user)) {
                if ($user['is_work'] == 0) {
                    return fail('此用户已被锁定');
                } else {
                    return $this->setLogin($user['id']);
                }
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
     * @return mixed|void
     */
    public function sendSmsCode(array $param)
    {
        $code = makeRandStr(4);
        Cache::set('smsCode' . $param['m_tel'], $code, 300);
        $sms = new SmsService();
        return $sms->sendSms($param['m_tel'],config('sms.codeTemplateId'),[$code]);
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
                $data = [
                    'uuid' => makeUuid(),
                    'uid' => $param['m_tel'],
                    'mobile' => $param['m_tel'],
                    'pid' => $pid,
                    'is_work' => 1,
                    'add_date' => time()
                ];
                if(isset($param['m_pwd'])) {
                    if(empty($param['m_pwd'])) {
                        return fail('密码为空');
                    } else {
                        $data['pwd'] = makePassword($param['m_pwd']);
                    }
                }
                $userId = Db::name('user')->insertGetId($data);
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
                                    ->update(['miniapp_openid'=>$data['openid'],'uid'=>$phone,'mobile'=>$phone]);
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
     * 公众号登录
     * @param array $param
     * @return array
     */
    public function wechatLogin(array $param) : array
    {
        try {
            $appId = config('wechat.mp.appid');
            $appSecret = config('wechat.mp.appsecret');
            if(isset($param['loginCode']) && !empty($param['loginCode'])) {
                //添加会员
                $pid = $param['pid'];
                $parentUser = Db::name('user')->where('id',$pid)->find();
                if(empty($parentUser) || $parentUser['is_fx'] == 0) {
                    $pid = 0;
                }
                $curl_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appId.'&secret='.$appSecret.'&code='.$param['loginCode'].'&grant_type=authorization_code';
                $data = curlGet($curl_url);
                if(isset($data['openid'])) {
                    $unionid = $data['unionid'] ?? '';
                    $wechatUser = Db::name('user')->where('wechat_openid',$data['openid'])->find();
                    if(empty($wechatUser)) {
                        //新会员
                        try {
                            $user = empty($unionid) ? [] : Db::name('user')->where('wechat_unionid',$unionid)->find();
                            if(!empty($user)) {
                                //微信其他端登录过
                                Db::name('user')
                                    ->where('wechat_unionid',$unionid)
                                    ->update(['wechat_openid'=>$data['openid']]);
                                $userId = $user['id'];
                            } else {
                                //新增
                                $userId = Db::name('user')->insertGetId([
                                    'uuid' => makeUuid(),
                                    'pid' => $pid,
                                    'wechat_openid' => $data['openid'],
                                    'wechat_unionid' => $unionid,
                                    'is_work' => 1,
                                    'add_date' => time()
                                ]);
                                Db::name('user')->where('id',$userId)->update(['uid'=>'user' . $userId]);
                            }
                            //注册成功后事件
                            Event::trigger('RegSuccess',['user_id' => $userId]);
                            return $this->setLogin($userId);
                        } catch (\Exception $e) {
                            return fail($e->getMessage());
                        }
                    } else {
                        //老会员
                        if ($wechatUser['is_work'] == 0) {
                            return fail('此用户已被锁定');
                        } else {
                            return $this->setLogin($wechatUser['id']);
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
        $auth = new JwtAuthService();
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
                $res['data']['uid'] = $user['uid'];
                $res['data']['avatar'] = getPic($user['avatar'],1);
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
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function setLogin(int $user_id) : array
    {
        //普通登录
        $user = Db::name('user')
            ->where('id', $user_id)
            ->find();
        $auth = new AuthService();
        $auth->setLogin('user',$user);
        Cookie::set('user_uid', $user['uid'], 86400);
        Cookie::set('user_pwd', empty($user['pwd']) ? '' : $user['pwd'], 86400);
        //jwt登录
        $jwtAuth = new JwtAuthService();
        $res = $jwtAuth->login(['id'=>$user_id,'type'=>'user']);
        if($res['code'] == 0) {
            header('token:' . $res['access_token']);
        }
        return $res;
    }

}