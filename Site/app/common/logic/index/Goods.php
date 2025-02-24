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
use app\common\service\Wechat as WechatService;
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
            $data['y_cat_id'] = $data->getData('cat_id');
            $data['sales'] = $data['sales'] + $data['initial_sales'];
            //规格
            $specKeyArr = [];
            $specStrArr = json_decode($data['spec_str'], true);
            //多规格
            if($data['multi_spec'] == 1) {
                foreach ($specStrArr as $key => $value) {
                    foreach ($value['items'] as $k => $v) {
                        if($v['select'] == 0) {
                            unset($specStrArr[$key]['items'][$k]);
                        } else {
                            if(!isset($specKeyArr[$key])) {
                                $specKeyArr[$key] = $v['title'];
                            }
                        }
                    }
                }
            }
            $data['spec_str'] = $specStrArr;
            $data['spec_key'] = implode('-',$specKeyArr);
            //根据规格初始化价格，库存
            $goodsPrice = GoodsPriceModel::where('goods_id',$data['id'])
                ->where('spec_key',$data['spec_key'])
                ->find();
            if(!empty($goodsPrice)) {
                $data['price'] = $goodsPrice['price'];
                $data['market_price'] = $goodsPrice['market_price'];
                $data['stock'] = $goodsPrice['stock'];
            }
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
            $catTitle = '';
            $parentCatId = 0;
            $list = GoodsModel::where('is_delete',0)
                ->where('is_sale', 1);
            if(isset($param['keys']) && $param['keys'] != '') {
                $list = $list->where('title','like','%'.$param['keys'].'%');
            }
            //所属ID
            if (isset($param['id']) && !empty($param['id'])) {
                $list = $list->where('id','in',$param['id']);
            }
            //所属分类
            if (isset($param['cat']) && !empty($param['cat'])) {
                $childCat = Db::name('goods_category')->where('parent_id',$param['cat'])->column('id');
                $parentCatId = !empty($childCat) ? $param['cat'] : Db::name('goods_category')->where('id',$param['cat'])->value('parent_id');
                foreach ($childCat as $v) {
                    $childCat = array_merge($childCat,Db::name('goods_category')->where('parent_id',$v)->column('id'));
                }
                array_push($childCat,$param['cat']);
                $list = $list->where('cat_id','in',$childCat);
                $catTitle = Db::name('goods_category')
                    ->where('id',$param['cat'])
                    ->value('title');
            }
            //所属品牌
            if (isset($param['brand']) && !empty($param['brand'])) {
                $list = $list->where('brand_id','in',$param['brand']);
            }
            //商品类型
            if (isset($param['type']) && !empty($param['type'])) {
                $list = $list->where('type','in',$param['type']);
            }
            //活动类型
            if(isset($param['activity_type']) && !empty($param['activity_type'])) {
                switch ($param['activity_type']) {
                    case "free" :
                        $list = $list->where('express_type',0);
                        $catTitle = '包邮商品';
                        break;
                    case "top" :
                        $list = $list->where('is_top',1);
                        $catTitle = '精品推荐';
                        break;
                    case "new" :
                        $list = $list->where('is_new',1);
                        $catTitle = '新品推荐';
                        break;
                    case "hot" :
                        $list = $list->where('is_hot',1);
                        $catTitle = '热卖商品';
                        break;
                }
            }
            //字段
            if (isset($param['field'])) {
                $param['field'] = preg_replace('/[^a-zA-Z0-9_,]/', '', $param['field']);
                if(!empty($param['field'])) {
                    $list = $list->field($param['field']);
                }
            }
            //排序
            if (isset($param['order']) && !empty($param['order'])) {
                $list = $list->order($param['order'],$param['order_type']);
            }
            $list = $list->order('sequence', 'desc')
                ->paginate($param['size'])
                ->toArray();
            $data = [
                'code' => 0,
                'msg' => '',
                'count' => $list['total'],
                'per_page' => $list['per_page'],
                'current_page' => $list['current_page'],
                'data' => $list['data']
            ];
            $data['cat_title'] = $catTitle;
            $data['parent_cat_id'] = $parentCatId;
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
            'per_page' => $list['per_page'],
            'current_page' => $list['current_page'],
            'data' => $list['data']
        ];
    }

    /**
     * 获取小程序太阳码(商品)
     * @param array $param
     * @return array
     */
    public function getMiniappQrcode(array $param = []) : array
    {
        try {
            $wechat = new WechatService();
            $accessToken = $wechat->getAccessToken('miniapp','GoodsQrcode');
            $qrcodePath = 'qrcode/miniapp_' . $param['user_id'] . '_' . $param['id'] . '.png';  //二维码地址
            $postUrl = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $accessToken;
            $res = $this->postUrl($postUrl,[
                'scene' => $param['user_id'] . '_' . $param['id'],
                'page' => 'pages/goods/detail'
            ]);
            file_put_contents(public_path() . 'upload' . DIRECTORY_SEPARATOR . 'pic' . DIRECTORY_SEPARATOR . 'qrcode' . DIRECTORY_SEPARATOR . 'miniapp_' . $param['user_id'] . '_' . $param['id'] . '.png', $res);
            return success(['qrcode'=>getPic($qrcodePath)]);
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * @param string $url
     * @param array $postData
     * @return bool|string
     */
    public function postUrl(string $url = '', array $postData = [])
    {
        // 发送post请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}