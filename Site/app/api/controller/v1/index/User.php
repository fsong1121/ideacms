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

use app\common\logic\index\User as UserLogic;
use think\response\Json;
use think\facade\Request;

class User extends Base
{
    /**
     * 获取会员信息
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getData() : Json
    {
        $res = $this->setParam(Request::param());
        if($res['code'] == 0) {
            $param = $res['data'];
            if(!empty($param['user_id'])) {
                $logic = new UserLogic();
                $res = $logic->readData($param);
            } else {
                $res = fail('请先登录',401);
            }
        }
        return json($res);
    }

    /**
     * 保存会员信息
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveInfo() : Json
    {
        if (Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    if(!isset($param['form_token'])) {
                        return json(fail('formToken为空'));
                    }
                    if ($param['name'] == '') {
                        return json(fail('昵称为空'));
                    }
                    $logic = new UserLogic();
                    $res = $logic->saveInfo($param);
                } else {
                    $res = fail('请先登录',401);
                }
            }
            return json($res);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 保存密码
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function savePwd() : Json
    {
        if (Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    if(!isset($param['form_token'])) {
                        return json(fail('formToken为空'));
                    }
                    if ($param['pwd'] == '' || $param['pwd'] != $param['vpwd']) {
                        return json(fail('密码为空或不同'));
                    }
                    $logic = new UserLogic();
                    $res = $logic->savePwd($param);
                } else {
                    $res = fail('请先登录',401);
                }
            }
            return json($res);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

}