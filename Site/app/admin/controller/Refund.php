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

use app\common\logic\admin\Refund as RefundLogic;
use think\facade\Request;
use think\response\Json;

class Refund extends Base
{
    /**
     * 列表
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/refund');
    }

    /**
     * 编辑
     * @param int $id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(int $id = 0) : string
    {
        $logic = new RefundLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/refund_edit');
        }
        else {
            return $this->fetch('statics/404.html');
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
        $k2 = Request::has('k2','get') ? $param['k2'] : '';
        $k3 = Request::has('k3','get') ? $param['k3'] : '';
        $k4 = Request::has('k4','get') ? $param['k4'] : '';
        $param['limit'] = $perPage;
        $param['keys'] = $keys;
        $param['k2'] = $k2;
        $param['k3'] = $k3;
        $param['k4'] = $k4;
        $logic = new RefundLogic();
        return json($logic->readList($param));
    }

    /**
     * 驳回
     * @return string
     */
    public function refuse() : string
    {
        $param = Request::param();
        $this->assign('ids', $param['id']);
        return $this->fetch('/refund_refuse');
    }

    /**
     * 保存驳回
     * @return Json
     */
    public function saveRefuse() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $logic = new RefundLogic();
            $data = $logic->saveRefuse($param);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 同意退款
     */
    public function agree() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $logic = new RefundLogic();
            $data = $logic->saveAgree($param);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

}