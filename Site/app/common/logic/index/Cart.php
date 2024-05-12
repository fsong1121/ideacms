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

use app\common\model\Cart as CartModel;
use app\common\logic\BaseLogic;
use think\facade\Db;

class Cart extends BaseLogic
{
    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $list = CartModel::where('user_id',$param['user_id'])
                ->order('id','desc')
                ->paginate($param['size'])
                ->toArray();
            $goodsAmount = 0;
            foreach ($list['data'] as $key => $value) {
                $goods = getGoodsInfo($value['goods_id'],$value['spec_key'],'title,pic,is_sale,is_delete,type');
                if(!empty($goods)) {
                    $isEffective = $goods['is_sale'] == 1 && $goods['is_delete'] == 0 && $goods['type'] == 0 ? 1 : 0;
                    $list['data'][$key]['title'] = $goods['title'];
                    $list['data'][$key]['pic'] = getPic($goods['pic']);
                    $list['data'][$key]['price'] = $goods['price'];
                    $list['data'][$key]['stock'] = $goods['stock'];
                    $list['data'][$key]['is_effective'] = $isEffective;
                    if($isEffective == 1) {
                        $goodsAmount = $goodsAmount + $value['amount'];
                    }
                }
            }
            $res['data'] = $list['data'];
            $res['goods_amount'] = $goodsAmount;
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
            $res = ['code' => 0,'msg' => 'success'];
            $goodsPrice = Db::name('goods_price')
                ->where('goods_id',$param['goods_id'])
                ->where('spec_key',$param['spec_key'])
                ->find();
            if(!empty($goodsPrice)) {
                if($goodsPrice['stock'] > 0) {
                    $amount = $param['amount'] > $goodsPrice['stock'] ? $goodsPrice['stock'] : $param['amount'];
                    $cart = CartModel::where('user_id', $param['user_id'])
                        ->where('goods_id', $param['goods_id'])
                        ->where('spec_key', $param['spec_key'])
                        ->find();
                    $data = [
                        'user_id' => $param['user_id'],
                        'goods_id' => $param['goods_id'],
                        'spec_key' => $param['spec_key'],
                        'spec_key_name' => $goodsPrice['spec_key_name'],
                        'amount' => $amount
                    ];
                    if (!empty($cart)) {
                        $data['amount'] = $amount + $cart['amount'];
                        $cart->save($data);
                    } else {
                        CartModel::create($data);
                    }
                } else {
                    $res['code'] = 500;
                    $res['msg'] = '库存不足';
                }
            } else {
                $res['code'] = 500;
                $res['msg'] = '商品不存在';
            }
            return $res;
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存修改数据
     * @param $param
     * @return array
     */
    public function editData($param) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $cart = CartModel::where('user_id',$param['user_id'])
                ->where('id',$param['id'])
                ->find();
            if(!empty($cart)) {
                $goodsPrice = Db::name('goods_price')
                    ->where('goods_id',$cart['goods_id'])
                    ->where('spec_key',$cart['spec_key'])
                    ->find();
                $amount = $param['amount'] > $goodsPrice['stock'] ? $goodsPrice['stock'] : $param['amount'];
                CartModel::update(['amount' => $amount], ['user_id' => $param['user_id'], 'id' => $param['id']]);
            } else {
                $res['code'] = 500;
                $res['msg'] = '非法提交被禁止';
            }
            return $res;
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
            $res = ['code' => 0,'msg' => 'success'];
            $ids = explode(",", $param['id']);
            CartModel::where('user_id',$param['user_id'])
                ->where('id','in',$ids)
                ->delete();
            return $res;
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }
}