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

use app\common\logic\admin\User as UserLogic;
use app\common\validate\admin\User as UserValidate;
use think\facade\Request;
use think\facade\Session;
use think\response\Json;

class User extends Base
{
    /**
     * 列表
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index() : string
    {
        $logic = new UserLogic();
        $this->assign('levelList', $logic->getLevelList());
        $this->assign('labelList', $logic->getLabelList());
        return $this->fetch('/user');
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
        $logic = new UserLogic();
        $this->assign('levelList', $logic->getLevelList());
        return $this->fetch('/user_add');
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
        $logic = new UserLogic();
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('data', $data);
            $logic = new UserLogic();
            $this->assign('levelList', $logic->getLevelList());
            $this->assign('labelList', $logic->getLabelList());
            return $this->fetch('/user_edit');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 获取列表
     * @return Json
     */
    public function getList() : Json
    {
        $param = Request::param();
        $perPage = Request::has('limit', 'get') ? $param['limit'] : $this->perPage;
        $keys = trimStr(Request::has('keys','get') ? $param['keys'] : '');
        $k2 = Request::has('k2','get') ? $param['k2'] : 0;
        $k3 = Request::has('k3','get') ? $param['k3'] : 0;
        $param['limit'] = $perPage;
        $param['keys'] = $keys;
        $param['k2'] = $k2;
        $param['k3'] = $k3;
        Session::set('adminKeys',$keys);
        Session::set('adminK2',$k2);
        Session::set('adminK3',$k3);
        $logic = new UserLogic();
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
            $validate = new UserValidate();
            $result = $validate->scene('info')->check($param);
            if (!$result) {
                return json(fail( $validate->getError()));
            } else {
                $logic = new UserLogic();
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
            $logic = new UserLogic();
            $data = $logic->delData($ids);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 修改密码
     * @param $uid
     * @return string
     */
    public function editPwd($uid) : string
    {
        $this->assign('user_id', $uid);
        return $this->fetch('/frame_edit_pwd');
    }

    /**
     * 保存密码修改
     * @return Json
     */
    public function savePwd() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $param = Request::param();
            $validate = new UserValidate();
            $result = $validate->scene('editPwd')->check($param);
            if (!$result) {
                return json(fail( $validate->getError()));
            } else {
                $logic = new UserLogic();
                $data = $logic->savePwd($param);
                return json($data);
            }
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 调整积分
     * @param $uid
     * @return string
     */
    public function editIntegral($uid) : string
    {
        $this->assign('user_id', $uid);
        return $this->fetch('/frame_edit_integral');
    }

    /**
     * 保存积分修改
     * @return Json
     */
    public function saveIntegral() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $param = Request::param();
            $validate = new UserValidate();
            $result = $validate->scene('editNum')->check($param);
            if (!$result) {
                return json(fail( $validate->getError()));
            } else {
                $data = UserLogic::saveUserAccount($param['m_uid'],$param['m_fee'],0,$param['m_info']);
                return json($data);
            }
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 调整余额
     * @param $uid
     * @return string
     */
    public function editBalance($uid) : string
    {
        $this->assign('user_id', $uid);
        return $this->fetch('/frame_edit_balance');
    }

    /**
     * 保存余额修改
     * @return Json
     */
    public function saveBalance() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $param = Request::param();
            $validate = new UserValidate();
            $result = $validate->scene('editNum')->check($param);
            if (!$result) {
                return json(fail( $validate->getError()));
            } else {
                $data = UserLogic::saveUserAccount($param['m_uid'],$param['m_fee'],1,$param['m_info']);
                return json($data);
            }
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 调整成长值
     * @param $uid
     * @return string
     */
    public function editGrowth($uid) : string
    {
        $this->assign('user_id', $uid);
        return $this->fetch('/frame_edit_growth');
    }

    /**
     * 保存成长值修改
     * @return Json
     */
    public function saveGrowth() : Json
    {
        if (Request::isPost() && Request::isAjax()){
            $param = Request::param();
            $validate = new UserValidate();
            $result = $validate->scene('editNum')->check($param);
            if (!$result) {
                return json(fail( $validate->getError()));
            } else {
                $data = UserLogic::saveUserAccount($param['m_uid'],$param['m_fee'],3,$param['m_info']);
                return json($data);
            }
        }
        else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 导出数据
     * @return Json
     */
    public function exportData() : Json
    {
        $param = Request::param();
        $param['limit'] = $param['limit'] ?? $this->perPage;
        $param['keys'] = Session::get('adminKeys');
        $param['k2'] = Session::get('adminK2');
        $param['k3'] = Session::get('adminK3');
        $logic = new UserLogic();
        $res = $logic->readList($param);
        $dataList = [];
        foreach ($res['data'] as $key => $value) {
            $dataList[$key]['ID'] = $value['id'];
            $dataList[$key]['用户名'] = $value['uid'];
            $dataList[$key]['积分'] = $value['integral'];
            $dataList[$key]['余额'] = $value['balance'];
            $dataList[$key]['成长值'] = $value['growth'];
            $dataList[$key]['佣金'] = $value['commission'];
            $dataList[$key]['昵称'] = $value['nickname'];
            $dataList[$key]['手机号'] = $value['mobile'];
            $dataList[$key]['会员等级'] = $value['level_id'];
            $dataList[$key]['分销商'] = $value['is_fx'] == 1 ? '是' : '否';
            $dataList[$key]['VIP'] = $value['is_vip'] == 1 ? '是' : '否';
            $dataList[$key]['状态'] = $value['is_work'] == 1 ? '激活' : '锁定';
            $dataList[$key]['添加时间'] = $value['add_date'];
        }
        $res['data'] = $dataList;
        return json($res);
    }

}