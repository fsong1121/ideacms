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
use app\common\model\Goods as GoodsModel;
use app\common\model\GoodsPrice as GoodsPriceModel;
use app\common\model\GoodsComment as GoodsCommentModel;
use app\common\logic\index\Coupon as CouponLogic;
use app\common\logic\index\Discount as DiscountLogic;
use think\facade\Db;

class Goods extends BaseLogic
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
        $time = time();
        $data = GoodsModel::where('id',$param['id'])
            ->where('is_sale',1)
            ->where('is_delete',0)
            ->find();
        if(!empty($data)) {
            $data->save(['hits'=>$data['hits']+1]);
            $data['sales'] = $data['sales'] + $data['initial_sales'];
            //幻灯片
            foreach ($data['slide'] as $key => $value) {
                $data['slide'][$key] = getPic($value);
            }
            //规格
            $data['spec_str'] = json_decode($data['spec_str'], true);
            //服务
            $data['service'] = Db::name('service')
                ->field('id,title,info')
                ->where('id','in',$data['service_ids'])
                ->where('is_show',1)
                ->order('sequence,id','desc')
                ->select()
                ->toArray();
            //优惠券
            $data['couponList'] = [];
            $logic = new CouponLogic();
            $res = $logic->readList([
                'goods_id' => $param['id'],
                'user_id' => $param['user_id'],
                'state' => 9
            ]);
            if($res['code'] == 0) {
                $data['couponList'] = $res['data'];
            }
            //促销
            $data['discountList'] = [];
            $discountLogic = new DiscountLogic();
            $res = $discountLogic->readList([
                'goods_id' => $param['id']
            ]);
            if($res['code'] == 0) {
                $data['discountList'] = $res['data'];
            }
            //评价
            $data['commentCount'] = GoodsCommentModel::where('goods_id',$param['id'])
                ->where('is_show',1)
                ->count();
            $commentList = GoodsCommentModel::where('goods_id',$param['id'])
                ->where('is_show',1)
                ->order(['is_top'=>'desc','id'=>'desc'])
                ->limit(1)
                ->select()
                ->toArray();
            foreach ($commentList as $key => $value) {
                $commentPic = [];
                $user = Db::name('user')->where('id',$value['user_id'])->find();
                $commentList[$key]['avatar'] = empty($user) ? getPic('',2) : getPic(empty($user['avatar']) ? '' : $user['avatar'],2);
                $commentList[$key]['user_name'] = strCut($value['user_name']);
                $commentList[$key]['new_add_date'] = formatDate($value['add_date'],4,2);
                foreach ($value['pic'] as $k => $v) {
                    $commentPic[$k] = getPic($v);
                }
                $commentList[$key]['pic'] = $commentPic;
            }
            $data['commentList'] = $commentList;
            return ['code' => 0,'data' => $data];
        } else {
            return fail('商品不存在');
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
            $list = GoodsModel::where('is_delete',0)
                ->where('is_sale', 1);
            if($param['keys'] != '') {
                $list = $list->where('title','like','%'.$param['keys'].'%');
            }
            //所属ID
            if (!empty($param['id'])) {
                $list = $list->where('id','in',$param['id']);
            }
            //所属分类
            if (!empty($param['cat'])) {
                $childCat = Db::name('goods_category')->where('parent_id',$param['cat'])->column('id');
                foreach ($childCat as $v) {
                    $childCat = array_merge($childCat,Db::name('goods_category')->where('parent_id',$v)->column('id'));
                }
                array_push($childCat,$param['cat']);
                $list = $list->where('cat_id','in',$childCat);
            }
            //所属品牌
            if (!empty($param['brand'])) {
                $list = $list->where('brand_id','in',$param['brand']);
            }
            //商品类型
            if (!empty($param['type'])) {
                $list = $list->where('type','in',$param['type']);
            }
            //活动类型
            if(!empty($param['activity_type'])) {
                switch ($param['activity_type']) {
                    case "free" :
                        $list = $list->where('express_type',0);
                        break;
                    case "top" :
                        $list = $list->where('is_top',1);
                        break;
                    case "new" :
                        $list = $list->where('is_new',1);
                        break;
                    case "hot" :
                        $list = $list->where('is_hot',1);
                        break;
                }
            }
            //字段
            if (!empty($param['field'])) {
                $list = $list->field($param['field']);
            }
            //排序
            if (!empty($param['order'])) {
                $list = $list->order($param['order'],$param['order_type']);
            }
            $list = $list->order('sequence', 'desc')
                ->paginate($param['size'])
                ->toArray();
            $data = [
                'code' => 0,
                'msg' => '',
                'count' => $list['total'],
                'data' => $list['data']
            ];
            if (!empty($param['cat'])) {
                $data['cat_title'] = Db::name('goods_category')
                    ->where('id',$param['cat'])
                    ->value('title');
            }
            return $data;
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 获取商品规格信息
     * @param int $goodsId
     * @param string $specKey
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSpec(int $goodsId = 0, string $specKey = '') : array
    {
        $data = GoodsPriceModel::where('goods_id',$goodsId)
            ->where('spec_key',$specKey)
            ->find();
        if(!empty($data)) {
            return ['code' => 0,'data' => $data];
        } else {
            return fail('商品不存在');
        }
    }

    /**
     * 获取商品评价列表
     * @param array $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCommentList(array $param = []) : array
    {
        $list = GoodsCommentModel::where('goods_id',$param['id'])
            ->where('is_show',1)
            ->order(['is_top'=>'desc','id'=>'desc'])
            ->paginate($param['size'])
            ->toArray();
        foreach ($list['data'] as $key => $value) {
            $commentPic = [];
            $user = Db::name('user')->where('id',$value['user_id'])->find();
            $list['data'][$key]['avatar'] = empty($user) ? getPic('',2) : getPic(empty($user['avatar']) ? '' : $user['avatar'],2);
            $list['data'][$key]['user_name'] = strCut($value['user_name']);
            $list['data'][$key]['new_add_date'] = formatDate($value['add_date'],4,2);
            foreach ($value['pic'] as $k => $v) {
                $commentPic[$k] = getPic($v);
            }
            $list['data'][$key]['pic'] = $commentPic;
        }
        return [
            'code' => 0,
            'msg' => '',
            'count' => $list['total'],
            'data' => $list['data']
        ];
    }
}