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

use app\common\logic\admin\GoodsSpec as GoodsSpecLogic;
use app\common\logic\admin\GoodsSpecItem as GoodsSpecItemLogic;
use app\common\validate\admin\Goods as GoodsValidate;
use think\facade\Request;
use think\response\Json;

class GoodsSpecItem extends Base
{
    /**
     * 列表
     * @param int $id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index(int $id = 0) : string
    {
        $logic = new GoodsSpecLogic();
        $data = $logic->readData($id);
        if(!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/goods_spec_item');
        } else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 添加
     * @param int $specID
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function create(int $specID = 0) : string
    {
        $logic = new GoodsSpecLogic();
        $data = $logic->readData($specID);
        if(!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/goods_spec_item_add');
        } else {
            return $this->fetch('statics/404.html');
        }
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
        $specLogic = new GoodsSpecLogic();
        $logic = new GoodsSpecItemLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            $this->assign('items', implode(PHP_EOL, explode(',',$data['items'])));
            $this->assign('spec', $specLogic->readData($data['spec_id']));
            return $this->fetch('/goods_spec_item_edit');
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
        $logic = new GoodsSpecItemLogic();
        return json($logic->readList($param));
    }

    /**
     * 保存
     */
    public function save() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new GoodsValidate();
            $result = $validate->scene('specItem')->check($param);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $logic = new GoodsSpecItemLogic();
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
            $logic = new GoodsSpecItemLogic();
            $data = $logic->delData($ids);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }
}