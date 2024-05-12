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

use app\common\model\Bill as BillModel;
use app\common\model\Order as OrderModel;
use app\common\model\OrderGoods as OrderGoodsModel;
use app\common\logic\BaseLogic;
use think\facade\Db;

class Bill extends BaseLogic
{
    /**
     * 获取可开票列表
     * @param array $param
     * @return array
     */
    public function readOrderList(array $param = []) : array
    {
        try {
            $time = time();
            $billOrderIds = BillModel::where('user_id',$param['user_id'])
                ->where('state','<',3)
                ->column('order_id');
            $list = OrderModel::where('user_id',$param['user_id'])
                ->where('order_state',4)
                ->where('id','not in',$billOrderIds)
                ->field('id,order_sn');
            if(config('shop.bill_time') > 0) {
                $list = $list->where('add_date','>=', $time - config('shop.bill_time') * 30 * 86400);
            }
            $list = $list->order('id','desc')
                ->paginate($param['size'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $orderGoods = OrderGoodsModel::where('order_id',$value['id'])
                    ->where('state',0)
                    ->field('id,goods_id,amount,price,spec_key_name')
                    ->select()
                    ->toArray();
                foreach ($orderGoods as $k => $v) {
                    $goods = getGoodsInfo($v['goods_id'],'','title,pic');
                    $orderGoods[$k]['goods_pic'] = getPic($goods['pic']);
                    $orderGoods[$k]['goods_title'] = $goods['title'];
                }
                $list['data'][$key]['goods_list'] = $orderGoods;
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
     * 读取订单数据
     * @param array $param
     * @return array
     */
    public function readOrderData(array $param = []) : array
    {
        try {
            $time = time();
            $res = ['code' => 0,'msg' => 'success'];
            $billOrderIds = BillModel::where('user_id',$param['user_id'])
                ->where('state','<',3)
                ->column('order_id');
            $data = OrderModel::where('user_id',$param['user_id'])
                ->where('id',$param['id'])
                ->where('id','not in',$billOrderIds)
                ->field('id,pay_price,refund_price');
            if(config('shop.bill_time') > 0) {
                $data = $data->where('add_date','>=', $time - config('shop.bill_time') * 30 * 86400);
            }
            $data = $data->find();
            if(!empty($data)) {
                $data['bill_price'] = formatPrice($data['pay_price'] - $data['refund_price']);
                $res['data'] = $data;
            } else {
                $res['code'] = 500;
                $res['msg'] = '订单不存在或已开票';
            }
            return $res;
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 获取可开票列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = BillModel::where('user_id',$param['user_id'])
                ->order('id','desc')
                ->paginate($param['size'])
                ->toArray();
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
     * 读取开票数据
     * @param array $param
     * @return array
     */
    public function readData(array $param = []) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $data = BillModel::where('user_id',$param['user_id'])
                ->where('id',$param['id'])
                ->find();
            if(!empty($data)) {
                $data['order_sn'] = OrderModel::where('id',$data['order_id'])->value('order_sn');
                $data['pic'] = getPic($data['tax_pic']);
                $res['data'] = $data;
            } else {
                $res['code'] = 500;
                $res['msg'] = '开票信息不存在';
            }
            return $res;
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存数据
     * @param $param
     * @return array
     */
    public function saveData($param) : array
    {
        try {
            $data = [
                'sn' => makeOrderSn('BI'),
                'order_id' => $param['order_id'],
                'user_id' => $param['user_id'],
                'type' => $param['type'],
                'tax_title' => $param['title'],
                'tax_sn' => $param['sn'],
                'fee' => $param['fee'],
                'info' => $param['info'],
                'state' => 1,
                'add_date' => time()
            ];
            BillModel::create($data);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 删除
     * @param array $param
     * @return array
     */
    public function delData(array $param = []) : array
    {
        try {
            $ids = explode(",", $param['id']);
            BillModel::where('user_id',$param['user_id'])
                ->where('id','in',$ids)
                ->where('state',3)
                ->delete();
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

}