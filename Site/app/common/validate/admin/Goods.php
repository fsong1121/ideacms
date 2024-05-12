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

namespace app\common\validate\admin;

use think\Validate;

class Goods extends Validate
{
    protected $rule =   [
        'm_title'  => 'require',
        'm_spec'  => 'require',
        'm_items'  => 'require',
        'm_cat'  => 'require',
        'm_pic'  => 'require',
        'm_brand' => 'integer',
        'm_item_price'  => 'require',
        'm_item_market_price'  => 'require',
        'm_item_cost_price'  => 'require',
        'm_item_stock'  => 'require',
        'm_item_sku'  => 'require',
        'm_item_weight'  => 'require',
        'm_item_volume'  => 'require',
        'm_express_type' => 'checkExpress',
        'm_express_price' => 'require',
        'm_express_template' => 'integer',
        'm_commission'  => 'require',
        'm_integral'  => 'integer',
        'm_growth'  => 'integer',
        'm_initial_sales'  => 'integer',
        'm_hits'  => 'integer',
        'm_px'  => 'integer'
    ];

    protected $message  =   [
        'm_title.require' => '名称为空',
        'm_spec.require' => '所属模板为空',
        'm_items.require' => '规格项为空',
        'm_cat.require' => '所属分类为空',
        'm_pic.require' => '商品图片为空',
        'm_brand.integer' => '所属品牌无效',
        'm_item_price.require' => '销售价为空',
        'm_item_market_price.require' => '划线价为空',
        'm_item_cost_price.require' => '成本价为空',
        'm_item_stock.require' => '商品库存为空',
        'm_item_sku.require' => '商品SKU为空',
        'm_item_weight.require' => '商品重量为空',
        'm_item_volume.require' => '商品体积为空',
        'm_express_price.require' => '固定运费为空',
        'm_express_template.integer' => '运费模板无效',
        'm_commission.require' => '佣金为空',
        'm_integral.integer' => '赠送积分无效',
        'm_growth.integer' => '成长值无效',
        'm_initial_sales.integer' => '原始销量无效',
        'm_hits.integer' => '人气无效',
        'm_px.integer' => '排序无效',
    ];

    /**
     * 验证是否选择运费模板
     * @param $value
     * @param $rule
     * @param array $data
     * @return bool|string
     */
    protected function checkExpress($value,$rule,$data=[])
    {
        if($value == 2 && $data['m_express_template'] == 0) {
            return '运费模板为空';
        }
        return true;
    }

    protected $scene = [
        'type' => ['m_title'],
        'spec' => ['m_title','m_px'],
        'specItem' => ['m_title','m_items','m_px','m_spec'],
        'goods' => ['m_title','m_cat','m_pic','m_brand','m_item_price','m_item_market_price','m_item_cost_price','m_item_stock','m_item_sku','m_item_weight','m_item_volume','m_express_type','m_express_price','m_express_template','m_commission','m_integral','m_growth','m_initial_sales','m_hits','m_px'],
    ];
}