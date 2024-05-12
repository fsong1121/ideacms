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

use app\common\model\LoginLog as LoginLogModel;
use think\facade\Request;
use think\response\Json;

class LoginLog extends Base
{
    /**
     * 列表
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/login_log');
    }

    /**
     * 获取列表
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function getList() : Json
    {
        $param = Request::param();
        $perPage = Request::has('limit', 'get') ? $param['limit'] : $this->perPage;
        $keys = trimStr(Request::has('keys','get') ? $param['keys'] : '');
        $list = LoginLogModel::where('id', '>', '0');
        if($keys != '') {
            $list = $list->where('uid', 'like' , '%' . $keys . '%');
        }
        $list = $list->order('id', 'desc')
            ->paginate($perPage)
            ->toArray();
        $data = [
            'code' => 0,
            'msg' => '',
            'count' => $list['total'],
            'data' => $list['data']
        ];
        return json($data);
    }
}