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

use app\common\logic\admin\Bill as BillLogic;
use think\facade\Request;
use think\response\Json;

class Bill extends Base
{
    /**
     * 列表
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/bill');
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
        $logic = new BillLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/bill_edit');
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
        $param['limit'] = $perPage;
        $param['keys'] = $keys;
        $param['k3'] = $param['k3'] ?? '';
        $param['k4'] = $param['k4'] ?? '';
        $logic = new BillLogic();
        return json($logic->readList($param));
    }

    /**
     * 保存
     */
    public function save() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            if($param['m_state'] == 1) {
                $param['m_tax_no'] = '';
                $param['m_pic'] = '';
                $param['m_reason'] = '';
            }
            if($param['m_state'] == 2) {
                if(empty($param['m_tax_no']) || empty($param['m_pic'])) {
                    return json(fail('发票编号或发票图片为空'));
                }
                $param['m_reason'] = '';
            }
            if($param['m_state'] == 3) {
                if(empty($param['m_reason'])) {
                    return json(fail('驳回原因为空'));
                }
                $param['m_tax_no'] = '';
                $param['m_pic'] = '';
            }
            $logic = new BillLogic();
            $data = $logic->saveData($param);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }
}