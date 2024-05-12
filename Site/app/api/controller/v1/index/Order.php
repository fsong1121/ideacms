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

use app\common\logic\index\Order as OrderLogic;
use app\common\validate\index\Order as OrderValidate;
use think\response\Json;
use think\facade\Request;

class Order extends Base
{
    /**
     * 获取各状态订单数量
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStateNumber() : Json
    {
        $res = $this->setParam(Request::param());
        if($res['code'] == 0) {
            $param = $res['data'];
            if(!empty($param['user_id'])) {
                $logic = new OrderLogic();
                $res = $logic->getStateNumber($param);
            } else {
                $res = fail('请先登录',401);
            }
        }
        return json($res);
    }

    /**
     * 获取内容
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
                $logic = new OrderLogic();
                $res = $logic->readData($param);
            } else {
                $res = fail('请先登录',401);
            }
        }
        return json($res);
    }

    /**
     * 获取列表
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList() : Json
    {
        $res = $this->setParam(Request::param());
        if($res['code'] == 0) {
            $param = $res['data'];
            if(!empty($param['user_id'])) {
                $param['size'] = $param['size'] ?? $this->perPage;
                $logic = new OrderLogic();
                $res = $logic->readList($param);
            } else {
                $res = fail('请先登录',401);
            }
        }
        return json($res);
    }

    /**
     * 确认订单
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function fillData() : Json
    {
        if (Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    $logic = new OrderLogic();
                    $res = $logic->fillData($param);
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
     * 保存
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save() : Json
    {
        if (Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    $validate = new OrderValidate();
                    $result = $validate->check($param);
                    if (!$result) {
                        $res = fail($validate->getError());
                    } else {
                        $logic = new OrderLogic();
                        $res = $logic->saveData($param);
                    }
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
     * 取消
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function cancel() : Json
    {
        if (Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    $logic = new OrderLogic();
                    $res = $logic->cancelData($param);
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
     * 删除
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delete() : Json
    {
        if (Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    $logic = new OrderLogic();
                    $res = $logic->delData($param);
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
     * 收货
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function receipt() : Json
    {
        if (Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    $logic = new OrderLogic();
                    $res = $logic->receiptData($param);
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
     * 获取快递信息
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getExpress() : Json
    {
        $res = $this->setParam(Request::param());
        if($res['code'] == 0) {
            $param = $res['data'];
            if(!empty($param['user_id'])) {
                $logic = new OrderLogic();
                $res = $logic->getExpress($param);
            } else {
                $res = fail('请先登录',401);
            }
        }
        return json($res);
    }

}