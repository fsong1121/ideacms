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

use app\common\model\FinanceDetail as FinanceDetailModel;
use think\facade\Request;
use think\facade\Db;
use think\response\Json;

class Finance extends Base
{
    /**
     * 列表
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/finance');
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
        $k3 = Request::has('k3','get') ? $param['k3'] : '';
        $k4 = Request::has('k4','get') ? $param['k4'] : '';
        $list = FinanceDetailModel::where('id', '>', '0');
        if($keys != '') {
            $list = $list->where('user_id', 'in' , Db::name('user')->where('uid|mobile|nickname', 'like' , '%' . $keys . '%')->column('id'));
        }
        if($k2 != '') {
            $list = $list->where('type',$k2);
        }
        if($k3 != '' && $k4 != ''){
            $list = $list->where('add_date','>=',strtotime($k3))->where('add_date','<=',strtotime($k4));
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