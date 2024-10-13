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

use app\common\model\GoodsCategory as GoodsCategoryModel;
use app\common\model\Goods as GoodsModel;
use app\common\model\Brand as BrandModel;
use app\common\model\GoodsSpec as GoodsSpecModel;
use app\common\model\GoodsSpecItem as GoodsSpecItemModel;
use app\common\model\GoodsPrice as GoodsPriceModel;
use app\common\model\Service as ServiceModel;
use app\common\model\Unit as UnitModel;
use app\common\model\ExpressTemplate as ExpressTemplateModel;
use app\common\model\Card as CardModel;
use think\facade\Db;

class Goods extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return GoodsModel|array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        return GoodsModel::find($id);
    }

    /**
     * 获取列表
     * @param array $param
     * @param int $is_delete
     * @return array
     */
    public function readList(array $param = [], int $is_delete = 0) : array
    {
        try {
            $list = GoodsModel::where('is_delete',$is_delete);
            if($param['keys'] != '') {
                $list = $list->where('title','like','%'.$param['keys'].'%');
            }
            if($param['k2'] != '') {
                $list = $list->where('cat_id',$param['k2']);
            }
            $list = $list->order(['sequence'=>'desc','id'=>'desc'])
                ->paginate($param['limit'])
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
     * 保存数据
     * @param $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveData($param) : array
    {
        $list = new GoodsModel();
        if(isset($param['m_id'])) {
            $list = GoodsModel::where('id',$param['m_id'])->find();
        }
        try {
            $list->startTrans();
            $m_express_type = $param['m_type'] > 0 ? 0 : $param['m_express_type'];
            $m_express_price = $param['m_type'] > 0 ? 0.00 : $param['m_express_price'];
            $m_express_template = $param['m_type'] > 0 ? 0 : $param['m_express_template'];
            $data = [
                'title' => $param['m_title'],
                'subtitle' => $param['m_subtitle'],
                'type' => $param['m_type'],
                'cat_id' => $param['m_cat'],
                'brand_id' => $param['m_brand'],
                'supplier_id' => $param['m_supplier'],
                'express_type' => $m_express_type,
                'express_price' => $m_express_price,
                'express_template_id' => $m_express_template,
                'spu' => $param['m_spu'],
                'pic' => $param['m_pic'],
                'slide' => $param['m_slide'],
                'video' => '',
                'video_pic' => '',
                'unit' => $param['m_unit'],
                'initial_sales' => $param['m_initial_sales'],
                'multi_spec' => $param['m_multi_spec'],
                'spec_str' => $param['m_spec_str'],
                'service_ids' => $param['m_service'],
                'info' => $param['m_info'],
                'other_info' => $param['m_other_info'],
                'keywords' => $param['m_keys'],
                'description' => $param['m_des'],
                'commission' => $param['m_commission'],
                'integral' => $param['m_integral'],
                'growth' => $param['m_growth'],
                'hits' => $param['m_hits'],
                'is_top' => $param['m_top'],
                'is_full_free' => $param['m_full_free'],
                'is_new' => $param['m_new'],
                'is_hot' => $param['m_hot'],
                'is_sale' => $param['m_sale'],
                'sequence' => $param['m_px']
            ];
            //如果是新增
            if(!isset($param['m_id'])) {
                $data['add_date'] = time();
            }
            $list->save($data);
            $goodsId = $list->id;
            //添加规格价格
            if(isset($param['m_id'])) {
                //如果是编辑先删除现有规格，暂时这样处理
                GoodsPriceModel::destroy(['goods_id' => $goodsId]);
            }
            $allStock = 0;
            $specList = [];
            $item_picture = explode(",", $param['m_item_picture']);
            $item_price = explode(",", $param['m_item_price']);
            $item_market_price = explode(",", $param['m_item_market_price']);
            $item_cost_price = explode(",", $param['m_item_cost_price']);
            $item_stock = explode(",", $param['m_item_stock']);
            $item_sku = explode(",", $param['m_item_sku']);
            $item_weight = explode(",", $param['m_item_weight']);
            $item_volume = explode(",", $param['m_item_volume']);
            $item_card = explode(",", $param['m_item_card']);
            $item_spec_key = explode(",", $param['m_item_spec_key']);
            $item_spec_key_name = explode(",", $param['m_item_spec_key_name']);
            //如果是卡密商品
            if($param['m_type'] == 1) {
                foreach ($item_card as $value) {
                    if(empty($value)) {
                        return fail('请选择卡密');
                    }
                }
            }
            foreach ($item_spec_key as $key => $value){
                $specList[$key]['goods_id'] = $goodsId;
                $specList[$key]['spec_key'] = $value;
                $specList[$key]['spec_key_name'] = $item_spec_key_name[$key];
                $specList[$key]['price'] = $item_price[$key];
                $specList[$key]['market_price'] = $item_market_price[$key];
                $specList[$key]['cost_price'] = $item_cost_price[$key];
                $specList[$key]['weight'] = $item_weight[$key];
                $specList[$key]['volume'] = $item_volume[$key];
                $specList[$key]['card_id'] = $item_card[$key];
                $specList[$key]['sku'] = $item_sku[$key];
                $specList[$key]['pic'] = str_replace(request()->domain(),'',str_replace('/upload/pic/','',$item_picture[$key]));
                $specList[$key]['stock'] = $item_stock[$key];
                $allStock = $allStock + $specList[$key]['stock'];
            }
            $specPrice = new GoodsPriceModel();
            $specPrice->saveAll($specList);
            $goodsPrice = GoodsPriceModel::where('goods_id',$goodsId)
                ->order('price','asc')
                ->find()
                ->toArray();
            $upData = [
                'price' => $goodsPrice['price'],
                'market_price' => $goodsPrice['market_price'],
                'stock' => $allStock
            ];
            GoodsModel::where('id',$goodsId)->update($upData);
            $list->commit();
            return success();
        }
        catch (\Exception $e) {
            $list->rollback();
            return fail($e->getMessage());
        }
    }

    /**
     * 软删除(放入回收站)
     * @param $ids
     * @return array
     */
    public function softDelData($ids) : array
    {
        $ids = explode(",", $ids);
        try {
            GoodsModel::update(['is_delete' => 1],['id' => $ids]);
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 从回收站恢复
     * @param $ids
     * @return array
     */
    public function recoveryData($ids) : array
    {
        $ids = explode(",", $ids);
        try {
            GoodsModel::update(['is_delete' => 0],['id' => $ids]);
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 删除(彻底删除)
     * @param $ids
     * @return array
     */
    public function delData($ids) : array
    {
        $err_num = 0;
        $ids = explode(",", $ids);
        try {
            foreach ($ids as $value) {
                $data = GoodsModel::where('id',$value)->findOrEmpty();
                if (!$data->isEmpty()) {
                    $order = Db::name('order_goods')->where('goods_id',$value)->findOrEmpty();
                    if(empty($order)) {
                        Db::name('goods_price')->where('goods_id' , $value)->delete(); //删除规格价格
                        Db::name('cart')->where('goods_id' , $value)->delete(); //删除购物车
                        Db::name('mark')->where('goods_id' , $value)->delete(); //删除足迹
                        Db::name('collect')->where('info_id' , $value)->where('type_id',0)->delete(); //删除收藏
                        $data->delete();
                    }
                    else {
                        $err_num = $err_num + 1;
                    }
                }
            }
            if($err_num > 0){
                return success([],'有' . $err_num . '个商品有关联订单，未删除');
            }
            else {
                return success();
            }
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 读取分类数据
     * @param int $id
     * @return GoodsCategoryModel|array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readCatData(int $id = 0)
    {
        return GoodsCategoryModel::find($id);
    }

    /**
     * 保存分类数据
     * @param $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveCatData($param) : array
    {
        $list = new GoodsCategoryModel();
        if(isset($param['m_id'])) {
            $list = GoodsCategoryModel::find($param['m_id']);
        }
        try {
            $deep = 1;
            if($param['m_parent_id'] > 0) {
                $deep = GoodsCategoryModel::where('id', $param['m_parent_id'])->value('deep') + 1;
            }
            $data = [
                'parent_id' => $param['m_parent_id'],
                'title' => $param['m_title'],
                'wap_title' => $param['m_wap_title'],
                'pic' => $param['m_pic'],
                'info' => $param['m_info'],
                'keywords' => $param['m_keys'],
                'description' => $param['m_des'],
                'sequence' => $param['m_px'],
                'deep' => $deep,
                'is_top' => $param['m_top'],
                'is_show' => $param['m_show']
            ];
            if(isset($param['m_id'])) { //如果是编辑
                $first_child = GoodsCategoryModel::where('parent_id',$param['m_id'])->column('id');
                if(!empty($first_child)) {
                    $deep1 = $deep + 1;
                    GoodsCategoryModel::update(['deep'=>$deep1],['parent_id'=>$param['m_id']]);
                    $second_child = GoodsCategoryModel::where('parent_id','in',$first_child)->column('id');
                    if(!empty($second_child)) {
                        $deep2 = $deep1 + 1;
                        GoodsCategoryModel::update(['deep'=>$deep2],['parent_id'=>$first_child]);
                    }
                }
            }
            $list->save($data);
            $this->setCat();
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 删除分类
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delCatData($id) : array
    {
        $data = GoodsCategoryModel::where('id',$id)->find();
        if(!empty($data)) {
            $goods = GoodsModel::where('cat_id',$id)->find();
            if(!empty($goods)) {
                return fail('分类下有商品,不能删除!');
            } else {
                $sonList = GoodsCategoryModel::where('parent_id',$id)->findOrEmpty();
                if(!$sonList->isEmpty()) {
                    return fail('分类下有子栏目,不能删除!');
                } else {
                    $data->delete();
                    $this->setCat();
                }
            }
        }
        return success();
    }

    /**
     * 获取品牌列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBrandList() : array
    {
        return BrandModel::where('is_show','>','0')
            ->order(['is_top'=>'desc','sequence'=>'desc'])
            ->select()
            ->toArray();
    }

    /**
     * 获取单位列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUnitList() : array
    {
        return UnitModel::where('is_show','>','0')
            ->order('sequence','desc')
            ->select()
            ->toArray();
    }

    /**
     * 获取服务列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getServiceList() : array
    {
        return ServiceModel::where('is_show','>','0')
            ->order('sequence','desc')
            ->select()
            ->toArray();
    }

    /**
     * 获取快递模板
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getExpressTemplate() : array
    {
        return ExpressTemplateModel::where('id','>',0)
            ->order('sequence','desc')
            ->select()
            ->toArray();
    }

    /**
     * 获取规格模板列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGoodsSpec() : array
    {
        $goodsSpec = GoodsSpecModel::where('id','>','0')
            ->order('sequence','desc')
            ->select()
            ->toArray();
        return fail('获取成功',200,$goodsSpec);
    }

    /**
     * 获取规格模板下的规格详情(添加)
     * @param int $specId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGoodsSpecItem(int $specId = 0) : array
    {
        $i = 1;
        $data = ['spec' => []];
        $goodsSpecItem = GoodsSpecItemModel::where('spec_id',$specId)
            ->order('sequence','desc')
            ->select()
            ->toArray();
        foreach ($goodsSpecItem as $key => $value) {
            $data['spec'][$key]['id'] = $key+1;
            $data['spec'][$key]['title'] = $value['title'];
            $data['spec'][$key]['value'] = [];
            $options = [];
            $items = explode(',',$value['items']);
            foreach ($items as $k => $v) {
                $options[$k]['id'] = $i;
                $options[$k]['title'] = $v;
                $i = $i + 1;
            }
            $data['spec'][$key]['options'] = $options;
        }
        return fail('获取成功',200,$data);
    }

    /**
     * 获取规格模板下的规格详情(编辑)
     * @param int $goodsId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getEditGoodsSpecItem(int $goodsId = 0) : array
    {
        $i = 1;
        $data = ['spec' => []];
        $goods = GoodsModel::where('id',$goodsId)->find();
        $specArr = json_decode($goods['spec_str'], true);
        foreach ($specArr as $key => $value) {
            $data['spec'][$key]['id'] = $key+1;
            $data['spec'][$key]['title'] = trimStr($value['spec']);
            $data['spec'][$key]['value'] = [];
            $options = [];
            foreach ($value['items'] as $k => $v) {
                $options[$k]['id'] = $i;
                $options[$k]['title'] = $v['title'];
                if($v['select'] == 1) {
                    array_push($data['spec'][$key]['value'],$i);
                }
                $i = $i + 1;
            }
            $data['spec'][$key]['options'] = $options;
        }
        return fail('获取成功',200,$data);
    }

    /**
     * 获取规格价格(编辑)
     * @param int $goodsId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSkuData(int $goodsId = 0) : array
    {
        $data = [];
        $goodsPrice = GoodsPriceModel::where('goods_id',$goodsId)->select()->toArray();
        foreach ($goodsPrice as $key => $value) {
            if(!empty($value['spec_key'])) {
                $data['['.$value['spec_key'].'][picture]'] = $value['pic'];
                $data['['.$value['spec_key'].'][site_price]'] = $value['price'];
                $data['['.$value['spec_key'].'][market_price]'] = $value['market_price'];
                $data['['.$value['spec_key'].'][cost_price]'] = $value['cost_price'];
                $data['['.$value['spec_key'].'][stock]'] = $value['stock'];
                $data['['.$value['spec_key'].'][sku]'] = $value['sku'];
                $data['['.$value['spec_key'].'][weight]'] = $value['weight'];
                $data['['.$value['spec_key'].'][volume]'] = $value['volume'];
                $data['['.$value['spec_key'].'][card_id]'] = $value['card_id'];
            } else {
                $data['picture'] = $value['pic'];
                $data['site_price'] = $value['price'];
                $data['market_price'] = $value['market_price'];
                $data['cost_price'] = $value['cost_price'];
                $data['stock'] = $value['stock'];
                $data['sku'] = $value['sku'];
                $data['weight'] = $value['weight'];
                $data['volume'] = $value['volume'];
                $data['card_id'] = $value['card_id'];
            }
        }

        return fail('获取成功',200,$data);
    }

    /**
     * 设置推荐
     * @param array $param
     * @return array
     */
    public function setTop(array $param = []) : array
    {
        try {
            GoodsModel::where('id','in',$param['m_id'])->save(['is_top'=>$param['m_top']]);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 设置上下架
     * @param array $param
     * @return array
     */
    public function setSale(array $param = []) : array
    {
        try {
            GoodsModel::where('id','in',$param['m_id'])->save(['is_sale'=>$param['m_sale']]);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 获取卡密列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCardList() : array
    {
        $cardList = CardModel::where('id','>','0')
            ->order('id','desc')
            ->select()
            ->toArray();
        foreach ($cardList as $key => $value) {
            $cardList[$key]['value'] = $value['id'];
            $cardList[$key]['key'] = $value['title'];
        }
        return fail('获取成功',200,$cardList);
    }


}