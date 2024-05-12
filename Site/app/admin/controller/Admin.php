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

use app\common\logic\admin\Admin as AdminLogic;
use app\common\validate\admin\Admin as AdminValidate;
use think\facade\Request;
use think\response\Json;

class Admin extends Base
{
    /**
     * 列表
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/admin');
    }

    /**
     * 添加
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function create() : string
    {
        $logic = new AdminLogic();
        $roleList = $logic->getRoleList();
        $this->assign('roleList', $roleList);
        return $this->fetch('/admin_add');
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
        $logic = new AdminLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            $roleList = $logic->getRoleList();
            $this->assign('roleList', $roleList);
            return $this->fetch('/admin_edit');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 列表
     * @return Json
     */
    public function getList() : Json
    {
        $param = Request::param();
        $perPage = Request::has('limit', 'get') ? $param['limit'] : $this->perPage;
        $keys = trimStr(Request::has('keys','get') ? $param['keys'] : '');
        $param['limit'] = $perPage;
        $param['keys'] = $keys;
        $logic = new AdminLogic();
        return json($logic->readList($param));
    }

    /**
     * 保存
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new AdminValidate();
            $result = $validate->check($param);
            if (!$result) {
                return json(fail( $validate->getError()));
            } else {
                $logic = new AdminLogic();
                $data = $logic->saveData($param);
                return json($data);
            }
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 删除
     * @return Json
     */
    public function delete() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $ids = Request::has('m_id') ? Request::param('m_id') : '0';
            $ids = "0," . $ids;
            $logic = new AdminLogic();
            $data = $logic->delData($ids);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }
}