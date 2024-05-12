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
namespace app\common\taglib;

use think\template\TagLib;

class Idea extends TagLib
{
    /**
     * 定义标签列表
     */
    protected $tags   =  [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'ads'   => ['attr'=>'name,size,field,id,type,start,order,orderType','level'=>3],
        'goods'   => ['attr'=>'name,size,field,id,type,cat,shop,shop_cat,start,order,orderType','level'=>3],
        'topic'   => ['attr'=>'name,size,field,id,start,order,orderType','level'=>3],
        'article'   => ['attr'=>'name,size,field,id,cat,start,order,orderType','level'=>3],

    ];

    /**
     * 广告标签
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagAds($tag, $content) : string
    {
        $name = $tag['name']; //必填,标签前缀
        $size = empty($tag['size']) ? 10 : $tag['size'];  //显示条数
        $field = empty($tag['field']) ? "*" : $tag['field'];  //查询字段
        $id = empty($tag['id']) ? 0 : $tag['id'];  //查询ID,可以是多个
        $type = empty($tag['type']) ? '' : $tag['type'];  //广告类型 0:普通广告 1:pc幻灯片 2:手机幻灯片 3:小程序幻灯片
        $start = empty($tag['start']) ? 0 : $tag['start'];  //从第几开始
        $order = empty($tag['order']) ? '' : $tag['order'];  //按什么排序
        $orderType = empty($tag['orderType']) ? 'desc' : $tag['orderType'];  //排序类型

        $sql = "select ".$field." from ".env('database.prefix', 'idea_')."ads where is_show = 1";
        //所属ID
        if (!empty($id)) {
            $sql = $sql . " and id in(".$id.")";
        }
        //所属分类
        if (!empty($type)) {
            $sql = $sql . " and type = " . $type;
        }
        //排序
        if (!empty($order)) {
            $sql = $sql . " order by ".$order." ".$orderType.",id desc";
        }
        else {
            $sql = $sql . " order by id " . $orderType;
        }
        //没有找到显示的内容
        $empty  = isset($tag['empty']) ? $tag['empty'] : '';

        $sql = $sql . " limit ".$start."," . $size;
        $parseStr = '<?php $__LIST__=\think\facade\Db::query("'.$sql.'");'; // 这里是查询数据
        $parseStr .= 'if( count($__LIST__)==0 ) : echo "' . $empty . '" ;';
        $parseStr .= 'else: ';
        $parseStr .= 'foreach($__LIST__ as $key=>$'.$name.'): ?>';
        $parseStr .= $content;
        $parseStr .= '<?php endforeach; endif; ?>';

        return $parseStr;
    }

    /**
     * 商品标签
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagGoods($tag, $content) : string
    {
        $name = $tag['name']; //必填,标签前缀
        $size = empty($tag['size']) ? 10 : $tag['size'];  //显示条数
        $field = empty($tag['field']) ? "*" : $tag['field'];  //查询字段
        $id = empty($tag['id']) ? 0 : $tag['id'];  //查询ID,可以是多个,用','隔开
        $cat = empty($tag['cat']) ? '0' : $tag['cat'];  //分类ID,可以是多个
        $shop = empty($tag['shop']) ? '0' : $tag['shop'];  //店铺ID,可以是多个
        $shop_cat = empty($tag['shop_cat']) ? '0' : $tag['shop_cat'];  //店铺分类分类ID,可以是多个
        $type = empty($tag['type']) ? '' : $tag['type'];  //商品类型(免邮，推荐，新品，热卖)
        $start = empty($tag['start']) ? 0 : $tag['start'];  //从第几开始
        $order = empty($tag['order']) ? '' : $tag['order'];  //按什么排序
        $orderType = empty($tag['orderType']) ? 'desc' : $tag['orderType'];  //排序类型

        $sql = "select ".$field." from ".env('database.prefix', 'idea_')."goods where is_sale = 1 and shop_id in (".implode(',',effective_shop_ids()).")";
        //所属ID
        if (!empty($id)) {
            $sql = $sql . " and id in(".$id.")";
        }
        //所属分类
        if(!empty($cat)){
            $sql = $sql . " and cat_id in(" . $cat . ")";
        }
        //所属店铺
        if(!empty($shop)){
            $sql = $sql . " and shop_id in(" . $shop . ")";
        }
        //所属店铺分类
        if(!empty($shop_cat)){
            $sql = $sql . " and shop_cat in(" . $shop_cat . ")";
        }
        //所属类型
        if(!empty($type)) {
            switch ($type) {
                case "free" :
                    $sql = $sql . " and is_free=1";
                    break;
                case "top" :
                    $sql = $sql . " and is_top=1";
                    break;
                case "new" :
                    $sql = $sql . " and is_new=1";
                    break;
                case "hot" :
                    $sql = $sql . " and is_hot=1";
                    break;
            }
        }
        //排序
        if (!empty($order)) {
            $sql = $sql . " order by ".$order." ".$orderType.",id desc";
        }
        else {
            $sql = $sql . " order by id " . $orderType;
        }
        //没有找到显示的内容
        $empty  = isset($tag['empty']) ? $tag['empty'] : '';

        $sql = $sql . " limit ".$start."," . $size;
        $parseStr = '<?php $__LIST__=\think\facade\Db::query("'.$sql.'");'; // 这里是查询数据
        $parseStr .= 'if( count($__LIST__)==0 ) : echo "' . $empty . '" ;';
        $parseStr .= 'else: ';
        $parseStr .= 'foreach($__LIST__ as $key=>$'.$name.'): ?>';
        $parseStr .= $content;
        $parseStr .= '<?php endforeach; endif; ?>';

        return $parseStr;
    }

    /**
     * 活动标签
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagTopic($tag, $content) : string
    {
        $name = $tag['name']; //必填,标签前缀
        $size = empty($tag['size']) ? 10 : $tag['size'];  //显示条数
        $field = empty($tag['field']) ? "*" : $tag['field'];  //查询字段
        $id = empty($tag['id']) ? 0 : $tag['id'];  //查询ID,可以是多个
        $start = empty($tag['start']) ? 0 : $tag['start'];  //从第几开始
        $order = empty($tag['order']) ? '' : $tag['order'];  //按什么排序
        $orderType = empty($tag['orderType']) ? 'desc' : $tag['orderType'];  //排序类型

        $sql = "select ".$field." from ".env('database.prefix', 'idea_')."topic where is_show = 1";
        //所属ID
        if (!empty($id)) {
            $sql = $sql . " and id in(".$id.")";
        }
        //排序
        if (!empty($order)) {
            $sql = $sql . " order by ".$order." ".$orderType.",id desc";
        }
        else {
            $sql = $sql . " order by id " . $orderType;
        }
        //没有找到显示的内容
        $empty  = isset($tag['empty']) ? $tag['empty'] : '';

        $sql = $sql . " limit ".$start."," . $size;
        $parseStr = '<?php $__LIST__=\think\facade\Db::query("'.$sql.'");'; // 这里是查询数据
        $parseStr .= 'if( count($__LIST__)==0 ) : echo "' . $empty . '" ;';
        $parseStr .= 'else: ';
        $parseStr .= 'foreach($__LIST__ as $key=>$'.$name.'): ?>';
        $parseStr .= $content;
        $parseStr .= '<?php endforeach; endif; ?>';

        return $parseStr;
    }

    /**
     * 文章标签
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagArticle($tag, $content) : string
    {
        $name = $tag['name']; //必填,标签前缀
        $size = empty($tag['size']) ? 10 : $tag['size'];  //显示条数
        $field = empty($tag['field']) ? "*" : $tag['field'];  //查询字段
        $id = empty($tag['id']) ? 0 : $tag['id'];  //查询ID,可以是多个
        $cat = empty($tag['cat']) ? '0' : $tag['cat'];  //分类ID,可以是多个
        $start = empty($tag['start']) ? 0 : $tag['start'];  //从第几开始
        $order = empty($tag['order']) ? '' : $tag['order'];  //按什么排序
        $orderType = empty($tag['orderType']) ? 'desc' : $tag['orderType'];  //排序类型

        $sql = "select ".$field." from ".env('database.prefix', 'idea_')."article where is_show = 1";
        //所属ID
        if (!empty($id)) {
            $sql = $sql . " and id in(".$id.")";
        }
        //所属分类
        if(!empty($cat)){
            $sql = $sql . " and cat_id in(" . $cat . ")";
        }
        //排序
        if (!empty($order)) {
            $sql = $sql . " order by ".$order." ".$orderType.",id desc";
        }
        else {
            $sql = $sql . " order by id " . $orderType;
        }
        //没有找到显示的内容
        $empty  = isset($tag['empty']) ? $tag['empty'] : '';

        $sql = $sql . " limit ".$start."," . $size;
        $parseStr = '<?php $__LIST__=\think\facade\Db::query("'.$sql.'");'; // 这里是查询数据
        $parseStr .= 'if( count($__LIST__)==0 ) : echo "' . $empty . '" ;';
        $parseStr .= 'else: ';
        $parseStr .= 'foreach($__LIST__ as $key=>$'.$name.'): ?>';
        $parseStr .= $content;
        $parseStr .= '<?php endforeach; endif; ?>';

        return $parseStr;
    }


}