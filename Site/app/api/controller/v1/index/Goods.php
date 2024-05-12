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
namespace app\api\controller\v1\index;

use app\common\logic\index\Goods as GoodsLogic;
use think\response\Json;
use think\facade\Request;

class Goods extends Base
{
    /**
     * 获取内容
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getData() : Json
    {
        $res = $this->setParam(Request::param());
        if($res['code'] == 0) {
            $param = $res['data'];
            $logic = new GoodsLogic();
            $res = $logic->readData($param);
        }
        return json($res);
    }

    /**
     * 获取列表
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList() : Json
    {
        $res = $this->setParam(Request::param(),0);
        if($res['code'] == 0) {
            $param = $res['data'];
            $param['size'] = $param['size'] ?? $this->perPage;
            $param['keys'] = trimStr($param['keys'] ?? '');
            $param['field'] = $param['field'] ?? '';  //查询字段
            $param['id'] = $param['id'] ?? '';  //查询ID,可以是多个,用','隔开
            $param['cat'] = $param['cat'] ?? '';  //分类ID,可以是多个
            $param['brand'] = $param['brand'] ?? '';  //品牌ID,可以是多个
            $param['type'] = $param['type'] ?? '';  //商品类型(0:普通商品 1:卡密/网盘 2:虚拟商品)
            $param['activity_type'] = $param['activity_type'] ?? '';  //活动类型(free:免邮，top:推荐，new:新品，hot:热卖)
            $param['start'] = $param['start'] ?? 0;  //从第几开始
            $param['order'] = $param['order'] ?? '';  //按什么排序
            $param['order_type'] = $param['order_type'] ?? 'desc';
            $logic = new GoodsLogic();
            $res = $logic->readList($param);
        }
        return json($res);
    }

    /**
     * 获取商品规格信息
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSpec() : Json
    {
        $res = $this->setParam(Request::param(),0);
        if($res['code'] == 0) {
            $param = $res['data'];
            $goodsId = $param['goods_id'];
            $specKey = $param['spec_key'];
            $logic = new GoodsLogic();
            $res = $logic->getSpec($goodsId,$specKey);
        }
        return json($res);
    }

    /**
     * 获取商品评价列表
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getComment() : Json
    {
        $res = $this->setParam(Request::param());
        if($res['code'] == 0) {
            $param = $res['data'];
            $logic = new GoodsLogic();
            $res = $logic->getCommentList($param);
        }
        return json($res);
    }

}