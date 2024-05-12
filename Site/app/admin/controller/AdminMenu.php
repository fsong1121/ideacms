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

use app\common\logic\admin\AdminMenu as AdminMenuLogic;
use app\common\logic\Base as BaseLogic;
use think\facade\Request;
use think\response\Json;

class AdminMenu extends Base
{
    /**
     * 列表
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/admin_menu');
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
        $logic = new AdminMenuLogic();
        $this->assign('adminMenu', $logic->getCat(0,'admin_menu'));
        return $this->fetch('/admin_menu_add');
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
        $logic = new AdminMenuLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('adminMenu', $logic->getCat(0,'admin_menu'));
            $this->assign('data', $data);
            return $this->fetch('/admin_menu_edit');
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
        try {
            $logic = new AdminMenuLogic();
            $list = $logic->getCat(0,'admin_menu',0,1);
            $data = [
                'code' => 0,
                'msg' => '',
                'count' => count($list),
                'data' => $list
            ];
            return json($data);
        } catch (\Exception $e) {
            return json(fail($e->getMessage()));
        }
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
            if (empty($param['m_title'])) {
                return json(fail( '菜单名称为空'));
            } else {
                $logic = new AdminMenuLogic();
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
            $logic = new AdminMenuLogic();
            $data = $logic->delData($ids);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

}