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

use think\facade\Db;
use think\facade\Session;
use think\facade\Cookie;
use think\facade\Request;
use think\facade\Event;

class Auth
{
    public string $uuid;

    /**
     * 检测是否登录
     * @param string $role
     * @return bool
     */
    public function checkLogin(string $role = 'user') : bool
    {
        $result = false;
        if(Session::has($role . '.uuid')) {
            $this->uuid = Session::get($role . '.uuid');
            $result = true;
        }
        else {
            //如果cookie存在账号就默认重新登录防止session过期频繁登录
            if (Cookie::has($role . '_uid') && Cookie::has($role . '_pwd')) {
                $user = Db::name($role)
                    ->where('uid', Cookie::get($role . '_uid'))
                    ->where('pwd', Cookie::get($role . '_pwd'))
                    ->where('is_work', 1)
                    ->findOrEmpty();
                if (!empty($user)) {
                    $this->setLogin($role, $user);
                    $this->uuid = $user['uuid'];
                    Cookie::set($role . '_uid', Cookie::get($role . '_uid'), 86400);
                    Cookie::set($role . '_pwd', Cookie::get($role . '_pwd'), 86400);
                    $result = true;
                }
            }
        }
        return $result;
    }

    /**
     * 设置登录
     * @param string $role
     * @param array $userInfo
     * @return bool
     */
    public function setLogin(string $role = 'user', array $userInfo = []) : bool
    {
        $result = false;
        if(!empty($userInfo)) {
            Session::set($role . '.uuid',$userInfo['uuid']);
            $result = true;
        }
        return $result;
    }

    /**
     * 登录
     * @param string $role
     * @param array $param
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function login(string $role = 'user', array $param = []) : array
    {
        $user = Db::name($role)
            ->where('uid', $param['m_uid'])
            ->where('pwd',makePassword($param['m_pwd']))
            ->find();
        if (!empty($user)) {
            if ($user['is_work'] == 0) {
                return ['code' => 500, 'msg' => '用户账号被禁用'];
            } else {
                //保存登录信息
                $this->setLogin($role,$user);
                Cookie::set($role . '_uid', $user['uid'], 86400);
                Cookie::set($role . '_pwd', $user['pwd'], 86400);
                //更新登录信息
                Db::name($role)
                    ->where('id', $user['id'])
                    ->update([
                        'last_login_time' => time(),
                        'last_login_ip' => Request::ip(),
                        'login_times' => $user['login_times'] + 1
                    ]);
                //登录日志
                Event::trigger('LoginLog', ['type' => $role, 'uid' => $user['uid'], 'ip' => Request::ip(), 'info' => '登录成功', 'data' => $param, 'state' => 1]);
                return ['code' => 0, 'msg' => '登录成功'];
            }
        } else {
            Event::trigger('LoginLog', ['type' => $role, 'uid' => $param['m_uid'], 'ip' => Request::ip(), 'info' => '用户名或密码错误', 'data' => $param, 'state' => 0]);
            return ['code' => 500, 'msg' => '用户账号或密码错误'];
        }
    }

    /**
     * 退出
     * @param string $role
     * @return bool
     */
    public function loginOut(string $role = 'user') : bool
    {
        Session::delete($role . '.uuid');
        Cookie::delete($role . '_uid');
        Cookie::delete($role . '_pwd');
        $this->uuid = '';
        return true;
    }

}