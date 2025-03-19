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
namespace app\common\model;

use think\facade\Db;

class Goods extends BaseModel
{
    /**
     * 获取图片地址
     * @param $value
     * @return string
     */
    public function getPicAttr($value) : string
    {
        return getPic($value);
    }

    /**
     * 获取栏目名称
     * @param $value
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCatIdAttr($value)
    {
        $cat = Db::name('goods_category')->where('id',$value)->find();
        return empty($cat) ? '' : $cat['title'];
    }

    /**
     * 获取图片列表
     * @param $value
     * @return array|string[]
     */
    public function getSlideAttr($value)
    {
        $slide = empty($value) ? [] : explode(',',$value);
        foreach ($slide as $key => $value) {
            $slide[$key] = getPic($value);
        }
        return $slide;
    }

    /**
     * 获取原始幻灯片列表
     * @param $value
     * @param $data
     * @return array|string[]
     */
    public function getYSlideAttr($value,$data)
    {
        return empty($data['slide']) ? [] : explode(',',$data['slide']);
    }

    /**
     * 获取服务列表
     * @param $value
     * @return array|string[]
     */
    public function getServiceIdsAttr($value)
    {
        return empty($value) ? [] : explode(',',$value);
    }
}