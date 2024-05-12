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
namespace app\common\logic\index;

use app\common\logic\BaseLogic;
use app\common\model\Order as OrderModel;
use app\common\model\OrderGoods as OrderGoodsModel;
use app\common\model\GoodsComment as GoodsCommentModel;

class Comment extends BaseLogic
{
    /**
     * 读取数据
     * @param array $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(array $param = []) : array
    {
        $orderIds = OrderModel::where('user_id', $param['user_id'])
            ->where('order_state',4)
            ->column('id');
        $data = OrderGoodsModel::where('order_id', 'in',$orderIds)
            ->where('id',$param['id'])
            ->where('state',0)
            ->find();
        if(!empty($data)) {
            $goods = getGoodsInfo($data['goods_id']);
            if(!empty($goods)) {
                $data['title'] = $goods['title'];
                $data['pic'] = getPic($goods['pic']);
            }
            $data['goods_rate'] = 5;
            $data['express_rate'] = 5;
            $data['service_rate'] = 5;
            $data['info'] = '';
            $data['picArr'] = [];
            if($data['is_comment'] == 1) {
                $goodsComment = GoodsCommentModel::where('order_id',$data['order_id'])
                    ->where('goods_id',$data['goods_id'])
                    ->where('spec_key_name',$data['spec_key_name'])
                    ->find();
                if(!empty($goodsComment)) {
                    $data['goods_rate'] = $goodsComment['goods_rate'];
                    $data['express_rate'] = $goodsComment['express_rate'];
                    $data['service_rate'] = $goodsComment['service_rate'];
                    $data['info'] = $goodsComment['info'];
                    $picArr = [];
                    foreach ($goodsComment['pic'] as $v) {
                        array_push($picArr,getPic($v));
                    }
                    $data['picArr'] = $picArr;
                }
            }
            return ['code' => 0,'data' => $data];
        } else {
            return fail('暂无数据');
        }
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $orderIds = OrderModel::where('user_id', $param['user_id'])
                ->where('order_state',4)
                ->column('id');
            $list = OrderGoodsModel::where('order_id', 'in',$orderIds)
                ->where('is_comment',$param['state'])
                ->where('state',0)
                ->order('id', 'desc')
                ->paginate($param['size'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $goods = getGoodsInfo($value['goods_id']);
                if(!empty($goods)) {
                    $list['data'][$key]['title'] = $goods['title'];
                    $list['data'][$key]['pic'] = getPic($goods['pic']);
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
     * @param array $param
     * @return array
     */
    public function saveData(array $param = []) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $userId = $param['user_id'];
            $orderIds = OrderModel::where('user_id', $userId)
                ->where('order_state',4)
                ->column('id');
            $data = OrderGoodsModel::where('order_id', 'in',$orderIds)
                ->where('id',$param['id'])
                ->where('is_comment',0)
                ->where('state',0)
                ->find();
            if(!empty($data)) {
                $data = [
                    'order_id' => $data['order_id'],
                    'goods_id' => $data['goods_id'],
                    'spec_key_name' => $data['spec_key_name'],
                    'user_id' => $userId,
                    'user_name' => getUserLevel($userId)['uid'],
                    'goods_rate' => $param['goods_rate'],
                    'express_rate' => $param['express_rate'],
                    'service_rate' => $param['service_rate'],
                    'pic' => $param['pic'],
                    'info' => $param['info'],
                    'add_date' => time()
                ];
                GoodsCommentModel::create($data);
                //更新订单商品为已评价
                OrderGoodsModel::update(['is_comment' => 1],['id' => $param['id']]);
            } else {
                return fail('商品不存在或已评价');
            }
            return $res;
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

}