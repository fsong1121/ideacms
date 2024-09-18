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

use app\common\logic\admin\Card as CardLogic;
use app\common\validate\admin\Card as CardValidate;
use think\facade\Request;
use think\response\Json;

class Card extends Base
{
    /**
     * 列表
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/card');
    }

    /**
     * 添加
     * @return string
     */
    public function create() : string
    {
        return $this->fetch('/card_add');
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
        $logic = new CardLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/card_edit');
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
        $logic = new CardLogic();
        return json($logic->readList($param));
    }

    /**
     * 保存
     */
    public function save() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new CardValidate();
            $result = $validate->scene('data')->check($param);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $logic = new CardLogic();
                $data = $logic->saveData($param);
                return json($data);
            }
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 删除
     */
    public function delete() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $ids = Request::has('m_id') ? Request::param('m_id') : '0';
            $ids = "0," . $ids;
            $logic = new CardLogic();
            $data = $logic->delData($ids);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 卡密列表
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function detail() : string
    {
        $id = Request::has('id') ? Request::param('id') : 0;
        $logic = new CardLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/card_detail');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 添加卡密
     * @return string
     */
    public function createDetail() : string
    {
        return $this->fetch('/card_detail_add');
    }

    /**
     * 编辑卡密
     * @param int $id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editDetail(int $id = 0) : string
    {
        $logic = new CardLogic();
        $data = $logic->readDetailData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/card_detail_edit');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 卡密列表
     * @return Json
     */
    public function getDetailList() : Json
    {
        $param = Request::param();
        $perPage = Request::has('limit', 'get') ? $param['limit'] : $this->perPage;
        $cardId = Request::has('card_id','get') ? $param['card_id'] : 0;
        $k2 = Request::has('k2','get') ? $param['k2'] : '';
        $keys = trimStr(Request::has('keys','get') ? $param['keys'] : '');
        $param['limit'] = $perPage;
        $param['keys'] = $keys;
        $param['card_id'] = $cardId;
        $param['k2'] = $k2;
        $logic = new CardLogic();
        return json($logic->readDetailList($param));
    }

    /**
     * 保存
     */
    public function saveDetail() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new CardValidate();
            $result = $validate->scene('detail')->check($param);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $logic = new CardLogic();
                $data = $logic->saveDetailData($param);
                return json($data);
            }
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 删除
     */
    public function deleteDetail() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $ids = Request::has('m_id') ? Request::param('m_id') : '0';
            $ids = "0," . $ids;
            $logic = new CardLogic();
            $data = $logic->delDetailData($ids);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

}