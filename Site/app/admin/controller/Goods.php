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

use app\common\logic\admin\Goods as GoodsLogic;
use app\common\validate\admin\Goods as GoodsValidate;
use think\facade\Request;
use think\facade\Session;
use think\response\Json;

class Goods extends Base
{
    /**
     * 列表
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index() : string
    {
        $logic = new GoodsLogic();
        $this->assign('catList', $logic->getCat());
        return $this->fetch('/goods');
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
        $logic = new GoodsLogic();
        $this->assign('catList', $logic->getCat());
        $this->assign('brandList', $logic->getBrandList());
        $this->assign('unitList', $logic->getUnitList());
        $this->assign('serviceList', $logic->getServiceList());
        $this->assign('expressTemplateList', $logic->getExpressTemplate());
        return $this->fetch('/goods_add');
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
        $logic = new GoodsLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('catList', $logic->getCat());
            $this->assign('brandList', $logic->getBrandList());
            $this->assign('unitList', $logic->getUnitList());
            $this->assign('serviceList', $logic->getServiceList());
            $this->assign('expressTemplateList', $logic->getExpressTemplate());
            $this->assign('data', $data);
            return $this->fetch('/goods_edit');
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
        $param['limit'] = $perPage;
        $param['keys'] = $keys;
        $param['k2'] = $k2;
        Session::set('goods_keys',$keys);
        Session::set('goods_k2',$k2);
        $logic = new GoodsLogic();
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
            $result = $validate->scene('goods')->check($param);
            if (!$result) {
                return json(fail( $validate->getError()));
            } else {
                $logic = new GoodsLogic();
                $data = $logic->saveData($param);
                return json($data);
            }
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 软删除
     */
    public function delete() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $ids = Request::has('m_id') ? Request::param('m_id') : '0';
            $ids = "0," . $ids;
            $logic = new GoodsLogic();
            $data = $logic->softDelData($ids);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 推荐
     */
    public function setTop() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $param = Request::param();
            $logic = new GoodsLogic();
            $data = $logic->setTop($param);
            return json($data);
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 上下架
     */
    public function setSale() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $param = Request::param();
            $logic = new GoodsLogic();
            $data = $logic->setSale($param);
            return json($data);
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 获取规格模板
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGoodsSpec() : Json
    {
        $logic = new GoodsLogic();
        return json($logic->getGoodsSpec());
    }

    /**
     * 获取规格模板下规格详情(添加)
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGoodsSpecItem() : Json
    {
        $specId = Request::param('product_type_id');
        $logic = new GoodsLogic();
        return json($logic->getGoodsSpecItem($specId));
    }

    /**
     * 获取规格模板下规格详情(编辑)
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getEditGoodsSpecItem() : Json
    {
        $goodsId = Request::param('goods_id');
        $logic = new GoodsLogic();
        return json($logic->getEditGoodsSpecItem($goodsId));
    }

    /**
     * 获取规格价格(编辑)
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSkuData() : Json
    {
        $goodsId = Request::param('goods_id');
        $logic = new GoodsLogic();
        return json($logic->getSkuData($goodsId));
    }

    /**
     * 添加规格
     * @return Json
     */
    public function specCreate() : Json
    {
        $param = Request::param();
        $data = [
            'code' => 200,
            'data' => [
                'id'=>$param['id']
            ],
            'msg' => '规格添加成功'
        ];
        return json($data);
    }

    /**
     * 删除规格
     * @return Json
     */
    public function specDelete() : Json
    {
        $data = [
            'code' => 200,
            'data' => '',
            'msg' => '规格删除成功'
        ];
        return json($data);
    }

    /**
     * 添加规格值
     * @return Json
     */
    public function specValueCreate() : Json
    {
        $param = Request::param();
        $data = [
            'code' => 200,
            'data' => [
                'id'=>$param['id']
            ],
            'msg' => '规格值添加成功'
        ];
        return json($data);
    }

    /**
     * 删除规格值
     * @return Json
     */
    public function specValueDelete() : Json
    {
        $data = [
            'code' => 200,
            'data' => '',
            'msg' => '规格值删除成功'
        ];
        return json($data);
    }

    /**
     * 选择商品
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function selectGoods() : string
    {
        $logic = new GoodsLogic();
        $this->assign('catList', $logic->getCat());
        return $this->fetch('/frame_goods');
    }

    /**
     * 获取卡密列表
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCardList() : Json
    {
        $logic = new GoodsLogic();
        return json($logic->getCardList());
    }

}