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

use app\common\model\GoodsComment as GoodsCommentModel;
use think\facade\Db;

class Comment extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return GoodsCommentModel|array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        return GoodsCommentModel::find($id);
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = GoodsCommentModel::where('id', '>', '0');
            if($param['keys'] != '') {
                $goodsIds = Db::name('goods')->where('title','like','%'.$param['keys'].'%')->column('id');
                $list = $list->where('goods_id','in',$goodsIds);
            }
            if($param['k3'] != '' && $param['k4'] != ''){
                $list = $list->where('add_date','>=',strtotime($param['k3']))->where('add_date','<=',strtotime($param['k4']));
            }
            $list = $list->order(['id'=>'desc'])
                ->paginate($param['limit'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $goods = Db::name('goods')->where('id',$value['goods_id'])->find();
                $goodsTitle = empty($goods) ? '' : $goods['title'];
                $list['data'][$key]['goods_title'] = $goodsTitle;
                $list['data'][$key]['rate'] = formatPrice(($value['goods_rate'] + $value['express_rate'] + $value['service_rate'])/3,1);
            }
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
        if(isset($param['m_id'])) {
            $list = GoodsCommentModel::find($param['m_id']);
            $replyDate = empty($param['m_reply_date']) ? time() : strtotime($param['m_reply_date']);
            $data = [
                'reply_info' => $param['m_reply_info'],
                'reply_date' => $replyDate
            ];
        } else {
            $list = new GoodsCommentModel();
            $addDate = empty($param['m_add_date']) ? time() : strtotime($param['m_add_date']);
            $data = [
                'goods_id' => $param['m_goods_id'],
                'user_name' => $param['m_user_name'],
                'info' => $param['m_info'],
                'pic' => $param['m_pic'],
                'add_date' => $addDate,
                'goods_rate' => $param['m_goods_rate'],
                'express_rate' => $param['m_express_rate'],
                'service_rate' => $param['m_service_rate'],
                'is_show' => $param['m_show']
            ];
        }
        try {
            $list->save($data);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 删除
     * @param $ids
     * @return array
     */
    public function delData($ids) : array
    {
        $ids = explode(",", $ids);
        try {
            GoodsCommentModel::destroy($ids);
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 设置显示
     * @param array $param
     * @return array
     */
    public function setShow(array $param = []) : array
    {
        try {
            GoodsCommentModel::where('id','in',$param['m_id'])->save(['is_show'=>$param['m_show']]);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }
}