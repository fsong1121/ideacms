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
use think\facade\Db;

class Console extends Base
{
    /**
     * 首页
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index() : string
    {
        $today = getDateStamp();
        $yesterday = ['b_time' => $today['b_time'] - 86400,'e_time' => $today['e_time'] - 86400,'title' => '昨日'];
        $week = getDateStamp(1);
        $lastWeek = ['b_time' => $week['b_time'] - 604800,'e_time' => $week['e_time'] - 604800,'title' => '上周'];
        //销售额
        $sales['total'] = Db::name('order')->where('order_state','>',1)->sum('pay_price') - Db::name('order')->where('order_state','>',1)->sum('refund_price');
        $sales['today'] = Db::name('order')->where('order_state','>',1)->where('add_date','between',[$today['b_time'],$today['e_time']])->sum('pay_price') - Db::name('order')->where('order_state','>',1)->where('add_date','between',[$today['b_time'],$today['e_time']])->sum('refund_price');
        $sales['yesterday'] = Db::name('order')->where('order_state','>',1)->where('add_date','between',[$yesterday['b_time'],$yesterday['e_time']])->sum('pay_price') - Db::name('order')->where('order_state','>',1)->where('add_date','between',[$yesterday['b_time'],$yesterday['e_time']])->sum('refund_price');
        $sales['week'] = Db::name('order')->where('order_state','>',1)->where('add_date','between',[$week['b_time'],$week['e_time']])->sum('pay_price') - Db::name('order')->where('order_state','>',1)->where('add_date','between',[$week['b_time'],$week['e_time']])->sum('refund_price');
        $sales['lastWeek'] = Db::name('order')->where('order_state','>',1)->where('add_date','between',[$lastWeek['b_time'],$lastWeek['e_time']])->sum('pay_price') - Db::name('order')->where('order_state','>',1)->where('add_date','between',[$lastWeek['b_time'],$lastWeek['e_time']])->sum('refund_price');
        $sales['comparedYesterday'] = empty($sales['today']) ? 0 : 100;
        if(!empty($sales['yesterday'])) {
            $sales['comparedYesterday'] = formatPrice(($sales['today'] - $sales['yesterday']) / $sales['yesterday']) * 100;
        }
        $sales['comparedLastWeek'] = empty($sales['week']) ? 0 : 100;
        if(!empty($sales['lastWeek'])) {
            $sales['comparedLastWeek'] = formatPrice(($sales['week'] - $sales['lastWeek']) / $sales['lastWeek']) * 100;
        }
        $this->assign('salesData', $sales);
        //访问量
        $visit['total'] = Db::name('visit')->count();
        $visit['today'] = Db::name('visit')->where('add_date','between',[$today['b_time'],$today['e_time']])->count();
        $visit['yesterday'] = Db::name('visit')->where('add_date','between',[$yesterday['b_time'],$yesterday['e_time']])->count();
        $visit['comparedYesterday'] = empty($visit['today']) ? 0 : 100;
        if(!empty($visit['yesterday'])) {
            $visit['comparedYesterday'] = formatPrice(($visit['today'] - $visit['yesterday']) / $visit['yesterday']) * 100;
        }
        $this->assign('visitData', $visit);
        //订单量
        $order['total'] = Db::name('order')->where('order_state','>',1)->count();
        $order['today'] = Db::name('order')->where('order_state','>',1)->where('add_date','between',[$today['b_time'],$today['e_time']])->count();
        $order['yesterday'] = Db::name('order')->where('order_state','>',1)->where('add_date','between',[$yesterday['b_time'],$yesterday['e_time']])->count();
        $order['comparedYesterday'] = empty($order['today']) ? 0 : 100;
        if(!empty($order['yesterday'])) {
            $order['comparedYesterday'] = formatPrice(($order['today'] - $order['yesterday']) / $order['yesterday']) * 100;
        }
        $this->assign('orderData', $order);
        //会员量
        $user['total'] = Db::name('user')->count();
        $user['today'] = Db::name('user')->where('add_date','between',[$today['b_time'],$today['e_time']])->count();
        $user['yesterday'] = Db::name('user')->where('add_date','between',[$yesterday['b_time'],$yesterday['e_time']])->count();
        $user['comparedYesterday'] = empty($user['user']) ? 0 : 100;
        if(!empty($user['yesterday'])) {
            $user['comparedYesterday'] = formatPrice(($user['today'] - $user['yesterday']) / $user['yesterday']) * 100;
        }
        $this->assign('userData', $user);

        $loginList = LoginLogModel::where('type','admin')
            ->order('id','desc')
            ->limit(6)
            ->select()
            ->toArray();
        $this->assign('logList', $loginList);
        return $this->fetch('/console');
    }

    /**
     * 获取销售数据
     * @return \think\response\Json
     */
    public function getSaleData() : Json
    {
        $data = [];
        $param = Request::param();
        $startDate = $param['start_date'];
        $endDate = $param['end_date'];
        $sTimestamp = strtotime($startDate);
        $eTimestamp = strtotime($endDate);
        // 计算日期段内有多少天
        $days = ($eTimestamp-$sTimestamp) / 86400 + 1;

        for($i = 0; $i < $days; $i++){
            $date = date('Y-m-d', $sTimestamp+(86400*$i));
            $b_date = strtotime($date . ' 00:00:00');
            $e_date = strtotime($date . ' 23:59:59');
            $data['title'][$i] = date('m-d', $sTimestamp+(86400*$i));
            $data['data'][$i] = Db::name('order')
                ->where('order_state','>',1)
                ->where('add_date','between',[$b_date,$e_date])
                ->sum('pay_price');
            //$data['data'][$i] = mt_rand(2000,5000);
        }
        return json($data);
    }
}