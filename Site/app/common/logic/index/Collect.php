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
use app\common\model\Collect as CollectModel;
use think\facade\Db;

class Collect extends BaseLogic
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
        $data = CollectModel::where('user_id',$param['user_id'])
            ->where('info_id',$param['id'])
            ->where('type_id',$param['type'])
            ->find();
        if(!empty($data)) {
            return ['code' => 0,'data' => $data];
        } else {
            return fail('暂无数据');
        }
    }

    /**
     * 获取列表$param
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = CollectModel::where('user_id', $param['user_id']);
            if(isset($param['type'])) {
                $list = $list->where('type_id', $param['type']);
            }
            $list = $list->order('id', 'desc')
                ->paginate($param['size'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $goodsId = $value['info_id'];
                $wapUrl = '/pages/goods/detail?id=' . $value['info_id'];
                $integral = 0;
                if($value['type_id'] == 2) {
                    //积分商品
                    $integralGoods = Db::name('addons_integral_goods')->where('id',$value['info_id'])->find();
                    if(!empty($integralGoods)) {
                        $goodsId = $integralGoods['goods_id'];
                        $integral = $integralGoods['integral'];
                    }
                    $wapUrl = '/pages/addons/integral/goodsDetail?id=' . $value['info_id'];
                }
                if($value['type_id'] == 3) {
                    //活动商品
                    $activityGoods = Db::name('addons_activity_goods')->where('id',$value['info_id'])->find();
                    if(!empty($activityGoods)) {
                        $goodsId = $activityGoods['goods_id'];
                    }
                    $wapUrl = '/pages/addons/activity/goodsDetail?id=' . $value['info_id'];
                }
                if($value['type_id'] == 4) {
                    //助力商品
                    $assistGoods = Db::name('addons_assist_goods')->where('id',$value['info_id'])->find();
                    if(!empty($assistGoods)) {
                        $goodsId = $assistGoods['goods_id'];
                    }
                    $wapUrl = '/pages/addons/assist/goodsDetail?id=' . $value['info_id'];
                }
                if($value['type_id'] == 5) {
                    //拼团商品
                    $combinationGoods = Db::name('addons_combination_goods')->where('id',$value['info_id'])->find();
                    if(!empty($combinationGoods)) {
                        $goodsId = $combinationGoods['goods_id'];
                    }
                    $wapUrl = '/pages/addons/combination/goodsDetail?id=' . $value['info_id'];
                }
                if($value['type_id'] == 6) {
                    //秒杀商品
                    $seckillGoods = Db::name('addons_seckill_goods')->where('id',$value['info_id'])->find();
                    if(!empty($seckillGoods)) {
                        $goodsId = $seckillGoods['goods_id'];
                    }
                    $wapUrl = '/pages/addons/seckill/goodsDetail?id=' . $value['info_id'];
                }
                $goods = getGoodsInfo($goodsId);
                if(!empty($goods)) {
                    $list['data'][$key]['info_title'] = $goods['title'];
                    $list['data'][$key]['info_pic'] = getPic($goods['pic']);
                    $list['data'][$key]['info_subtitle'] = $goods['subtitle'];
                    $list['data'][$key]['info_price'] = $goods['price'];
                    $list['data'][$key]['info_integral'] = $integral;
                    $list['data'][$key]['info_url'] = $wapUrl;
                }
            }
            return [
                'code' => 0,
                'msg' => '',
                'count' => $list['total'],
                'per_page' => $list['per_page'],
                'current_page' => $list['current_page'],
                'data' => $list['data']
            ];
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存数据(不存在就新增，否则删除)
     * @param array $param
     * @return array
     */
    public function saveData(array $param = []) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $userId = $param['user_id'];
            $list = CollectModel::where('user_id',$userId)
                ->where('info_id',$param['id'])
                ->where('type_id',$param['type'])
                ->find();
            if(empty($list)) {
                $data = [
                    'user_id' => $userId,
                    'info_id' => $param['id'],
                    'type_id' => $param['type'],
                    'add_date' => time()
                ];
                CollectModel::create($data);
            } else {
                CollectModel::where('user_id',$userId)
                    ->where('info_id',$param['id'])
                    ->where('type_id',$param['type'])
                    ->delete();
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
            CollectModel::where('user_id',$param['user_id'])
                ->where('id','in',$ids)
                ->delete();
            return $res;
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }
}