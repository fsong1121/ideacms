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
namespace app\api\controller\v1\index;

use app\BaseController;
use app\common\service\JwtAuth as AuthService;
use think\facade\Request;
use think\facade\Cache;

class Base extends BaseController
{
    protected int $perPage;
    protected string $accessToken = '';

    /**
     * 初始化
     */
    public function initialize()
    {
        $this->perPage = 20;
        $this->accessToken = Request::header('token','');
    }

    /**
     * 重新封装参数
     * @param array $param
     * @param int $checkLogin
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setParam(array $param = [],int $checkLogin = 1) : array
    {
        $accessToken = $this->accessToken;
        $param['user_id'] = 0;
        $time = time();
        //判断时间戳
        if (isset($param['time_stamp'])) {
            //有效请求时间为5分钟
            if ($time - $param['time_stamp'] > 300) {
                return fail('时间戳已过期');
            }
        } else {
            return fail('时间戳不存在');
        }
        //如果有form_token，需要判断表单是否正规提交
        if(isset($param['form_token'])) {
            $formToken = $param['form_token'];
            $cacheFormToken = Cache::get('formToken_' . $accessToken,'');
            if(empty($cacheFormToken) || $formToken == $cacheFormToken) {
                //提交并重置form_token
                $newFormToken = makeUuid();
                Cache::set('formToken_' . $accessToken, $newFormToken, 1200);
            } else {
                return fail('formToken为空或不正确');
            }
        }
        //判断是否登录
        if($checkLogin) {
            //通过token返回user_id
            if(!empty($accessToken)) {
                $auth = new AuthService();
                $res = $auth->checkLogin($accessToken);
                if ($res['code'] == 0) {
                    //返回最新accessToken
                    header('token:' . $res['access_token']);
                    $param['token'] = $res['access_token'];
                    $this->accessToken = $res['access_token'];
                    $param['user_id'] = $res['payload']['id'];
                    //返回formToken可用于验证表单提交
                    $formToken = Cache::get('formToken_' . $res['access_token'],'');
                    if(empty($formToken)) {
                        $formToken = makeUuid();
                        Cache::set('formToken_' . $res['access_token'], $formToken, 1200);
                    }
                    header('formtoken:' . $formToken);
                }
            }
        }
        return success($param);
    }

    /**
     * 方法不存在时执行
     * @param $name
     * @param $arguments
     * @return \think\response\Json
     */
    public function __call($name, $arguments)
    {
        return json(fail('抱歉，你访问的页面不存在！'));
    }
}