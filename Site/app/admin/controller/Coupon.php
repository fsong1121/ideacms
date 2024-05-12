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

use app\common\logic\admin\Coupon as CouponLogic;
use app\common\validate\admin\Coupon as CouponValidate;
use think\facade\Request;
use think\response\Json;

class Coupon extends Base
{
    /**
     * 列表
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/coupon');
    }

    /**
     * 添加
     * @return string
     */
    public function create() : string
    {
        return $this->fetch('/coupon_add');
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
        $logic = new CouponLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            $this->assign('goodsList', $logic->getGoodsList($data['goods_ids']));
            return $this->fetch('/coupon_edit');
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
        $logic = new CouponLogic();
        return json($logic->readList($param));
    }

    /**
     * 保存
     */
    public function save() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new CouponValidate();
            $result = $validate->check($param);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $logic = new CouponLogic();
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
            $logic = new CouponLogic();
            $data = $logic->delData($ids);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 领取详情
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function show() : string
    {
        $id = Request::has('id') ? Request::param('id') : 0;
        $logic = new CouponLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/coupon_info');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 领取列表
     * @return Json
     */
    public function getInfoList() : Json
    {
        $param = Request::param();
        $perPage = Request::has('limit', 'get') ? $param['limit'] : $this->perPage;
        $couponId = Request::has('coupon_id','get') ? $param['coupon_id'] : 0;
        $k2 = Request::has('k2','get') ? $param['k2'] : '';
        $param['limit'] = $perPage;
        $param['coupon_id'] = $couponId;
        $param['k2'] = $k2;
        $logic = new CouponLogic();
        return json($logic->readInfoList($param));
    }

    /**
     * 指定发放
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function give() : string
    {
        $param = Request::param();
        $logic = new CouponLogic();
        $data = $logic->readData($param['coupon_id']);
        if (!empty($data)) {
            $this->assign('data', $data);
            return $this->fetch('/coupon_give');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 保存发放
     * @return Json
     */
    public function saveGive() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $param = Request::param();
            $logic = new CouponLogic();
            $data = $logic->saveGive($param);
            return json($data);
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }
}