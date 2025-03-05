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
namespace app\common\logic\admin;

use app\common\model\Coupon as CouponModel;
use app\common\model\CouponUser as CouponUserModel;
use think\facade\Db;

class Coupon extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        return CouponModel::find($id);
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = CouponModel::where('id', '>', '0');
            if($param['keys'] != '') {
                $list = $list->where('title','like','%'.$param['keys'].'%');
            }
            $list = $list->order(['id'=>'desc'])
                ->paginate($param['limit'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $time = time();
                if($time < strtotime($value['b_date'])){
                    $list['data'][$key]['state'] = 0; //未开始
                }
                else if($time <= strtotime($value['e_date'])){
                    if($value['get_amount'] < $value['send_amount']) {
                        $list['data'][$key]['state'] = 1; //发放中
                    } else {
                        $list['data'][$key]['state'] = 2; //已发完
                    }
                }
                else {
                    $list['data'][$key]['state'] = -1; //已过期
                }
            }
            return [
                'code' => 0,
                'msg' => '',
                'count' => $list['total'],
                'data' => $list['data']
            ];
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存数据
     * @param $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveData($param) : array
    {
        $list = new CouponModel();
        if(isset($param['m_id'])) {
            $list = CouponModel::find($param['m_id']);
            if($param['m_amount'] < $list['send_amount']) {
                return fail('发放数量必须>=' . $list['send_amount']);
            }
        }
        try {
            $data = [
                'title' => $param['m_title'],
                'type' => $param['m_type'],
                'min_price' => $param['m_min_price'],
                'cut_price' => $param['m_cut_price'],
                'per_amount' => $param['m_per_amount'],
                'use_type' => $param['m_use_type'],
                'goods_ids' => $param['m_goods_ids'],
                'use_time' => $param['m_use_time'],
                'white_label_id' => 0,
                'black_label_id' => 0,
                'b_date' => $param['b_date'],
                'e_date' => $param['e_date']
            ];
            //如果非线下券
            if($param['m_type'] < 4) {
                $data['send_amount'] = $param['m_amount'];
            }
            //如果是新增
            if(!isset($param['m_id'])) {
                $data['uuid'] = makeUuid();
            }
            $list->save($data);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 删除
     * @param $ids
     * @return array
     */
    public function delData($ids) : array
    {
        $ids = explode(",", $ids);
        try {
            $cIds = CouponModel::where('id','in',$ids)->column('id');
            CouponUserModel::where('coupon_id','in',$cIds)->delete();
            CouponModel::where('id','in',$ids)->delete();
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 获取商品列表
     * @param $ids
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGoodsList($ids) : array
    {
        return Db::name('goods')
            ->where('id','in',$ids)
            ->field('id,pic,title')
            ->select()
            ->toArray();
    }

    /**
     * 获取领取列表
     * @param array $param
     * @return array
     */
    public function readInfoList(array $param = []) : array
    {
        try {
            $coupon = CouponModel::where('id',$param['coupon_id'])->find();
            $couponId = empty($coupon) ? 0 : $coupon['id'];
            $list = CouponUserModel::where('coupon_id', $couponId);
            if($param['k2'] != '') {
                $list = $list->where('is_use',$param['k2']);
            }
            $list = $list->order(['id'=>'desc'])
                ->paginate($param['limit'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key]['user_name'] = Db::name('user')->where('id',$value['user_id'])->value('uid');
            }
            return [
                'code' => 0,
                'msg' => '',
                'count' => $list['total'],
                'data' => $list['data']
            ];
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存指定发放
     * @param $param
     * @param int $is_repeat (是否重复)
     * @param int $type (来源)
     * @param int $activity_id (活动ID)
     * @return array|void
     */
    public function saveGive($param,int $is_repeat = 0,int $type = 0,int $activity_id = 0)
    {
        try {
            $time = time();
            $user = Db::name('user')->find($param['user_id']);
            if (!empty($user)) {
                if (isset($param['m_coupon_uuid'])) {
                    $coupon = CouponModel::where('uuid',$param['m_coupon_uuid'])->where('type',1)->find();
                    $param['m_coupon_id'] = empty($coupon) ? 0 : $coupon['id'];
                }
                $couponUser = [];
                if($is_repeat == 0) {
                    $couponUser = CouponUserModel::where('coupon_id', $param['m_coupon_id'])
                        ->where('user_id', $user['id'])
                        ->where('is_use', 0)
                        ->find();
                }
                if (empty($couponUser)) {
                    $coupon = CouponModel::where('id', $param['m_coupon_id'])
                        ->where('b_date','<=',$time)
                        ->where('e_date','>=',$time)
                        ->find();
                    if (!empty($coupon)) {
                        if ($coupon['send_amount'] > $coupon['get_amount']) {
                            //如果设置了领取上限，判断可领张数
                            if($coupon['per_amount'] > 0) {
                                $getCount = CouponUserModel::where('coupon_id', $param['m_coupon_id'])
                                    ->where('user_id', $user['id'])
                                    ->count();
                                if($getCount >= $coupon['per_amount']) {
                                    return fail("本券最多可领".$coupon['per_amount']."张");
                                }
                            }
                            $res = Db::name('coupon')
                                ->where('id', $param['m_coupon_id'])
                                ->where('version', $coupon['version'])
                                ->inc('get_amount')
                                ->inc('version')
                                ->update();
                            if ($res > 0) {
                                $endDate = strtotime($coupon['e_date']);
                                if($coupon['use_time'] > 0) {
                                    $endDate = time() + $coupon['use_time'];
                                }
                                CouponUserModel::create([
                                    'coupon_id' => $param['m_coupon_id'],
                                    'coupon_sn' => makeOrderSn('C'),
                                    'user_id' => $user['id'],
                                    'type' => $type,
                                    'activity_id' => $activity_id,
                                    'add_date' => time(),
                                    'end_date' => $endDate
                                ]);
                                return success();
                            } else {
                                $this->saveGive($param,$is_repeat,$type,$activity_id);
                            }
                        } else {
                            return fail("已领完啦");
                        }
                    } else {
                        return fail("优惠券不存在");
                    }
                } else {
                    return fail('已领取');
                }
            } else {
                return fail('会员不存在');
            }
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 执行导入
     * @param array $param
     * @return array
     */
    public function saveImport(array $param = []) : array
    {
        // 启动事务
        Db::startTrans();
        try {
            $dataListArr = explode(',',$param['m_data']);
            $saveData = [];
            $coupon = Db::name('coupon')
                ->where('id',$param['m_coupon_id'])
                ->find();
            if(!empty($coupon)) {
                foreach ($dataListArr as $value) {
                    $data = Db::name('coupon_user')
                        ->where('coupon_sn', $value)
                        ->find();
                    if (empty($data) && !empty($value)) {
                        array_push($saveData, [
                            'coupon_sn' => $value,
                            'coupon_id' => $param['m_coupon_id']
                        ]);
                    }
                }
                //新增发放量
                Db::name('coupon')
                    ->where('id', $coupon['id'])
                    ->inc('send_amount',count($saveData))
                    ->update();
                //新增优惠券明细
                Db::name('coupon_user')
                    ->limit(200)
                    ->insertAll($saveData);
            }
            // 提交事务
            Db::commit();
            return success();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return fail($e->getMessage());
        }
    }

    /**
     * 删除优惠券明细
     * @param $ids
     * @return array
     */
    public function delInfoData($ids) : array
    {
        $ids = explode(",", $ids);
        try {
            foreach ($ids as $id) {
                $couponUser = CouponUserModel::where('id',$id)
                    ->where('user_id',0)
                    ->find();
                if(!empty($couponUser)) {
                    //减少发放量
                    Db::name('coupon')
                        ->where('id', $couponUser['coupon_id'])
                        ->dec('send_amount')
                        ->update();
                    //删除
                    CouponUserModel::where('id',$id)->delete();
                }
            }
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

}