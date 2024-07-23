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

use app\common\logic\index\Address as AddressLogic;
use app\common\validate\index\Address as AddressValidate;
use think\response\Json;
use think\facade\Request;
use think\facade\Cache;
use think\facade\Db;

class Address extends Base
{
    /**
     * 获取省市区
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getArea() : Json
    {
        $areaList = [];
        $res = $this->setParam(Request::param());
        if($res['code'] == 0) {
            $param = $res['data'];
            if(!empty($param['user_id'])) {
                $areaList = Cache::get('areaList', []);
                if (empty($areaList)) {
                    $logic = new AddressLogic();
                    $areaProvince = $logic->getAreaList();
                    foreach ($areaProvince as $k1 => $v1) {
                        $areaList[$k1]['name'] = $v1['name'];
                        $areaList[$k1]['code'] = $v1['id'];
                        $areaList[$k1]['parentId'] = $v1['parent_id'];
                        $areaCity = $logic->getAreaList($v1['id']);
                        foreach ($areaCity as $k2 => $v2) {
                            $areaList[$k1]['children'][$k2]['name'] = $v2['name'];
                            $areaList[$k1]['children'][$k2]['code'] = $v2['id'];
                            $areaList[$k1]['children'][$k2]['parentId'] = $v2['parent_id'];
                            $areaCounty = $logic->getAreaList($v2['id']);
                            foreach ($areaCounty as $k3 => $v3) {
                                $areaList[$k1]['children'][$k2]['children'][$k3]['name'] = $v3['name'];
                                $areaList[$k1]['children'][$k2]['children'][$k3]['code'] = $v3['id'];
                                $areaList[$k1]['children'][$k2]['children'][$k3]['parentId'] = $v3['parent_id'];
                            }
                        }
                    }
                }
                //根据名称获取单省、市、县列表
                if(isset($param['province']) && isset($param['city'])) {
                    $newAreaList = [];
                    if(empty($param['province']) && empty($param['city'])) {
                        //省列表
                        $newAreaList = $areaList;
                    }
                    if(!empty($param['province']) && empty($param['city'])) {
                        //市列表
                        foreach ($areaList as $value) {
                            if($value['name'] == $param['province']) {
                                $newAreaList = $value['children'];
                            }
                        }
                    }
                    if(!empty($param['province']) && !empty($param['city'])) {
                        //县列表
                        foreach ($areaList as $value) {
                            if($value['name'] == $param['province']) {
                                foreach ($value['children'] as $value1) {
                                    if($value1['name'] == $param['city']) {
                                        $newAreaList = $value1['children'];
                                    }
                                }
                            }
                        }
                    }
                    $areaList = $newAreaList;
                }
            } else {
                return json(fail('请先登录',401));
            }
        }
        return json(success($areaList));
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
                $logic = new AddressLogic();
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
                $logic = new AddressLogic();
                $res = $logic->readList($param);
            } else {
                $res = fail('请先登录',401);
            }
        }
        return json($res);
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
                    if(!isset($param['form_token'])) {
                        return json(fail('formToken为空'));
                    }
                    $validate = new AddressValidate();
                    $result = $validate->check($param);
                    if (!$result) {
                        $res = fail($validate->getError());
                    } else {
                        $logic = new AddressLogic();
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
     * 设为默认
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setDefault() : Json
    {
        if (Request::isPost()) {
            $res = $this->setParam(Request::param());
            if($res['code'] == 0) {
                $param = $res['data'];
                if(!empty($param['user_id'])) {
                    $logic = new AddressLogic();
                    $res = $logic->setDefault($param);
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
                    $logic = new AddressLogic();
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

}