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
namespace app\admin\controller;

use app\common\logic\admin\Cash as CashLogic;
use think\facade\Request;
use think\response\Json;

class Cash extends Base
{
    /**
     * 列表
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/cash');
    }

    /**
     * 提现成功
     * @return Json
     */
    public function agree() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $logic = new CashLogic();
            $data = $logic->saveAgree($param);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 提现失败
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editCash() : string
    {
        $param = Request::param();
        $logic = new CashLogic();
        $data = $logic->readData($param['id']);
        if (!empty($data) && $data['cash_state'] == 0) {
            $this->assign('data', $data);
            return $this->fetch('/cash_edit');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 保存
     */
    public function saveCash() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $logic = new CashLogic();
            $data = $logic->saveCash($param);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 获取列表
     */
    public function getList() : Json
    {
        $param = Request::param();
        $perPage = Request::has('limit', 'get') ? $param['limit'] : $this->perPage;
        $keys = trimStr(Request::has('keys','get') ? $param['keys'] : '');
        $param['limit'] = $perPage;
        $param['keys'] = $keys;
        $param['k2'] = $param['k2'] ?? '';
        $param['k3'] = $param['k3'] ?? '';
        $param['k4'] = $param['k4'] ?? '';
        $logic = new CashLogic();
        return json($logic->readList($param));
    }

}