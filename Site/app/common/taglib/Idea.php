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
use think\facade\Db;

class Idea extends TagLib
{
    /**
     * 定义标签列表
     */
    protected $tags   =  [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'ads'   => ['attr'=>'name,size,field,id,type,start','level'=>3],
        'goods'   => ['attr'=>'name,size,field,id,cat,brand,type,activity,start','level'=>3],
        'article'   => ['attr'=>'name,size,field,id,cat,start','level'=>3],
        'category'   => ['attr'=>'name,field,id,parent,type','level'=>3],
    ];

    /**
     * 广告标签
     * @param array $tag
     * @param string $content
     * @return string
     */
    public function tagAds(array $tag, string $content) : string
    {
        $name = $tag['name']; //必填,标签前缀
        $size = empty($tag['size']) ? 10 : $tag['size'];  //显示条数
        $field = empty($tag['field']) ? "*" : $tag['field'];  //查询字段
        $id = empty($tag['id']) ? 0 : $tag['id'];  //查询ID,可以是多个
        $type = empty($tag['type']) ? '' : $tag['type'];  //广告类型 0:普通广告 1:pc幻灯片 2:移动端
        $start = empty($tag['start']) ? 0 : $tag['start'];  //从第几开始

        $sql = "select ".$field." from ".env('DB_PREFIX', 'idea_')."ads where is_show = 1";
        //所属ID
        if (!empty($id)) {
            $sql = $sql . " and id in(".$id.")";
        }
        //所属分类
        if (!empty($type)) {
            $sql = $sql . " and type = " . $type;
        }
        //排序
        $sql = $sql . " order by sequence desc,id desc";
        //没有找到显示的内容
        $empty  = $tag['empty'] ?? '';

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
     * @param array $tag
     * @param string $content
     * @return string
     */
    public function tagGoods(array $tag, string $content) : string
    {
        $name = $tag['name']; //必填,标签前缀
        $size = empty($tag['size']) ? 10 : $tag['size'];  //显示条数
        $field = empty($tag['field']) ? "*" : $tag['field'];  //查询字段
        $id = empty($tag['id']) ? 0 : $tag['id'];  //查询ID,可以是多个
        $cat = empty($tag['cat']) ? 0 : $tag['cat'];  //所属分类
        $brand = empty($tag['brand']) ? 0 : $tag['brand'];  //所属品牌
        $type = empty($tag['type']) ? '' : $tag['type'];  //商品类型
        $activity = empty($tag['activity']) ? '' : $tag['activity'];  //活动类型free，top，new，hot
        $start = empty($tag['start']) ? 0 : $tag['start'];  //从第几开始

        $sql = "select ".$field." from ".env('DB_PREFIX', 'idea_')."goods where is_delete = 0 and is_sale = 1";
        //所属ID
        if (!empty($id)) {
            $sql = $sql . " and id in(".$id.")";
        }
        //所属分类
        if (!empty($cat)) {
            $childCat = Db::name('goods_category')->where('parent_id',$cat)->column('id');
            foreach ($childCat as $v) {
                $childCat = array_merge($childCat,Db::name('goods_category')->where('parent_id',$v)->column('id'));
            }
            array_push($childCat,$cat);
            $sql = $sql . " and cat_id in(".implode(',', $childCat).")";
        }
        //所属品牌
        if (!empty($brand)) {
            $sql = $sql . " and brand_id in(".$brand.")";
        }
        //商品类型
        if (!empty($type)) {
            $sql = $sql . " and type in(".$type.")";
        }
        //活动类型
        if(!empty($activity)) {
            switch ($activity) {
                case "free" :
                    $sql = $sql . " and express_type = 0";
                    break;
                case "top" :
                    $sql = $sql . " and is_top = 1";
                    break;
                case "new" :
                    $sql = $sql . " and is_new = 1";
                    break;
                case "hot" :
                    $sql = $sql . " and is_hot = 1";
                    break;
            }
        }
        //排序
        $sql = $sql . " order by sequence desc,id desc";
        //没有找到显示的内容
        $empty  = $tag['empty'] ?? '';

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
     * @param array $tag
     * @param string $content
     * @return string
     */
    public function tagArticle(array $tag, string $content) : string
    {
        $name = $tag['name']; //必填,标签前缀
        $size = empty($tag['size']) ? 10 : $tag['size'];  //显示条数
        $field = empty($tag['field']) ? "*" : $tag['field'];  //查询字段
        $id = empty($tag['id']) ? 0 : $tag['id'];  //查询ID,可以是多个
        $cat = empty($tag['cat']) ? '' : $tag['cat'];  //所属栏目
        $start = empty($tag['start']) ? 0 : $tag['start'];  //从第几开始

        $sql = "select ".$field." from ".env('DB_PREFIX', 'idea_')."article where is_show = 1";
        //所属ID
        if (!empty($id)) {
            $sql = $sql . " and id in(".$id.")";
        }
        //所属栏目
        if (!empty($cat)) {
            $childCat = Db::name('article_category')->where('parent_id',$cat)->column('id');
            array_push($childCat,$cat);
            $sql = $sql . " and cat_id in(".implode(',', $childCat).")";
        }
        //排序
        $sql = $sql . " order by is_top desc,sequence desc,id desc";
        //没有找到显示的内容
        $empty  = $tag['empty'] ?? '';

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
     * 分类标签
     * @param array $tag
     * @param string $content
     * @return string
     */
    public function tagCategory(array $tag, string $content) : string
    {
        $name = $tag['name']; //必填,标签前缀
        $size = empty($tag['size']) ? 10 : $tag['size'];  //显示条数
        $field = empty($tag['field']) ? "*" : $tag['field'];  //查询字段
        $id = empty($tag['id']) ? 0 : $tag['id'];  //查询ID,可以是多个
        $type = empty($tag['type']) ? 'goods' : $tag['type'];  //类型 article:文章 goods:商品
        $parent = empty($tag['parent']) ? 0 : $tag['parent'];  //上级(可以是数字，也可以是变量(嵌套))

        $sql = "select " . $field . " from " . env('DB_PREFIX', 'idea_') . $type . "_category where is_show = 1";
        if(is_numeric($parent)) {
            $sql = $sql . " and parent_id = " . $parent;
        } else {
            $parent = $this->autoBuildVar($parent);
            $sql = $sql . " and parent_id = \".".$parent.".\"";
        }
        //所属ID
        if (!empty($id)) {
            $sql = $sql . " and id in(".$id.")";
        }
        //排序
        $sql = $sql . " order by is_top desc,sequence desc,id desc";
        $sql = $sql . " limit " . $size;
        //没有找到显示的内容
        $empty  = $tag['empty'] ?? '';

        $parseStr = '<?php $__LIST__=\think\facade\Db::query("'.$sql.'");'; // 这里是查询数据
        $parseStr .= 'if( count($__LIST__)==0 ) : echo "' . $empty . '" ;';
        $parseStr .= 'else: ';
        $parseStr .= 'foreach($__LIST__ as $key=>$'.$name.'): ?>';
        $parseStr .= $content;
        $parseStr .= '<?php endforeach; endif; ?>';

        return $parseStr;
    }


}