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

namespace app\common\service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use app\common\logic\BaseLogic;
use think\facade\Request;
use think\facade\Cache;
use think\facade\Db;

class JwtAuth
{
    protected array $jwtConfig = [];
    protected int $leewayTime = 0;

    /**
     * 初始化
     */
    public function __construct()
    {
        //基本配置
        $this->jwtConfig = [
            'key' => config('site.api_key'), //秘钥
            'ttl' => config('site.token_ttl') * 60, //access_token过期时间(7200为2小时)
            'refresh_ttl' => 86400 * config('site.refresh_token_ttl'), //refresh_token过期时间
        ];
    }

    /**
     * 登录后返回token
     * @param array $user
     * @return array
     */
    public function login(array $user = []) : array
    {
        $jwtList['code'] = 500;
        try {
            $time = time();
            $token = [
                "iss" => Request::host(), //签发者
                "aud" => Request::host(), //用户
                "iat" => $time,           //签发时间
                "nbf" => $time,           //生效时间
                "data" => [
                    'id' => $user['id'],
                    'type' => $user['type'] //user,admin等
                ]
            ];
            $access_token = $token;
            $access_token['scopes'] = 'role_access';
            $access_token['exp'] = $time + $this->jwtConfig['ttl'];

            $refresh_token = $token;
            $refresh_token['scopes'] = 'role_refresh';
            $refresh_token['exp'] = $time + $this->jwtConfig['refresh_ttl'];

            $jwtList = [
                'code' => 0,
                'msg' => 'success',
                'access_token' => JWT::encode($access_token, $this->jwtConfig['key'], "HS256")
            ];
            Db::name('token')->insert([
                'user_id' => $user['id'],
                'type' => $user['type'],
                'access_token' => $jwtList['access_token'],
                'refresh_token' => JWT::encode($refresh_token, $this->jwtConfig['key'], "HS256"),
                'create_time' => $time,
                'expires_time' => $time + $this->jwtConfig['refresh_ttl']
            ]);
            $baseLogic = new BaseLogic();
            $baseLogic->setToken($user['type']);
        }
        catch (\Exception $e) { //其他错误
            $jwtList['msg'] = $e->getMessage();
        }
        return $jwtList;
    }

    /**
     * 检测是否登录
     * @param string $token
     * @param string $type
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function checkLogin(string $token = '',string $type = 'user') : array
    {
        $status['code'] = 500;
        try {
            JWT::$leeway = $this->leewayTime;//当前时间减去多少，多用于签发和验证不同服务器上
            //$decoded = JWT::decode($token, $this->jwtConfig['key'], ['HS256']); //HS256方式，这里要和签发的时候对应
            $decoded = JWT::decode($token, new Key($this->jwtConfig['key'],'HS256')); //HS256方式，这里要和签发的时候对应
            if($type == $decoded->data->type) {
                $res['code'] = 0;
                $res['msg'] = 'success';
                $res['access_token'] = $token;
                $res['payload'] = [
                    'id' => $decoded->data->id,
                    'type' => $decoded->data->type
                ];
                $token_list = Cache::get($decoded->data->type . '_token', []);
                if (in_array($token, $token_list)) {
                    return $res;
                } else {
                    $status['msg'] = "token失效";
                    return $status;
                }
            } else {
                $status['msg'] = "token无效";
                return $status;
            }
        } catch (SignatureInvalidException $e) { //签名不正确
            $status['msg'] = "签名不正确";
            return $status;
        } catch (BeforeValidException $e) { // 签名在某个时间点之后才能用
            $status['msg'] = "token失效";
            return $status;
        } catch (ExpiredException $e) { // token过期，用refresh_token刷新
            $time = time();
            $token = Db::name('token')
                ->where('access_token',$token)
                ->where('type',$type)
                ->where('create_time','<=',$time)
                ->where('expires_time','>=',$time)
                ->find();
            if(!empty($token)) {
                try {
                    JWT::$leeway = $this->leewayTime;//当前时间减去多少，多用于签发和验证不同服务器上
                    $decoded = JWT::decode($token['refresh_token'], new Key($this->jwtConfig['key'],'HS256')); //HS256方式，这里要和签发的时候对应
                    $token_arr = [
                        "iss" => Request::host(), //签发者
                        "aud" => Request::host(), //用户
                        "iat" => $time,           //签发时间
                        "nbf" => $time,           //生效时间
                        "data" => [
                            'id' => $decoded->data->id,
                            'type' => $decoded->data->type //user,admin等
                        ],
                        "scopes" => 'role_access',
                        "exp" => $time + $this->jwtConfig['ttl']
                    ];
                    $new_token = JWT::encode($token_arr,$this->jwtConfig['key'],"HS256");
                    $res['code'] = 0;
                    $res['msg'] = 'success';
                    $res['access_token'] = $new_token;
                    $res['payload'] = [
                        'id' => $decoded->data->id,
                        'type' => $decoded->data->type
                    ];
                    Db::name('token')
                        ->where('refresh_token',$token['refresh_token'])
                        ->update(['access_token' => $new_token]);
                    $baseLogic = new BaseLogic();
                    $baseLogic->setToken($decoded->data->type);
                    return $res;
                } catch (\Exception $e) {
                    $status['msg'] = "refresh_token无效";
                    return $status;
                }
            }
            else {
                $status['msg'] = "token失效";
                return $status;
            }
        } catch (\Exception $e) { //其他错误
            //$status['msg'] = "未知错误";
            $status['msg'] = $e->getMessage();
            return $status;
        }
    }

    /**
     * 退出
     * @param string $token
     * @return array
     */
    public function loginOut(string $token = '') : array
    {
        $status['code'] = 500;
        try {
            $user_type = Db::name('token')->where('access_token',$token)->value('type');
            Db::name('token')->where('access_token',$token)->delete();
            $baseLogic = new BaseLogic();
            $baseLogic->setToken($user_type);
            $status['code'] = 0;
            $status['msg'] = 'success';
        } catch (\Exception $e) {
            $status['msg'] = $e->getMessage();
        }
        return $status;
    }

}