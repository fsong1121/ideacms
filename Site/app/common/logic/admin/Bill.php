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

use app\common\model\Bill as BillModel;
use think\facade\Db;

class Bill extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        $data = BillModel::find($id);
        if(!empty($data)) {
            $goodsList = [];
            $orderGoods = Db::name('order_goods')
                ->where('order_id',$data['order_id'])
                ->where('state',0)
                ->field('goods_id,amount,spec_key_name')
                ->select()
                ->toArray();
            foreach ($orderGoods as $k => $v) {
                $goods = getGoodsInfo($v['goods_id'],'','title');
                $str = $goods['title'];
                if(!empty($v['spec_key_name'])) {
                    $str = $str . '(' . $v['spec_key_name'] . ')';
                }
                $goodsList[$k] = $str . ' x' . $v['amount'];
            }
            $data['goods_list'] = $goodsList;
        }
        return $data;
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = BillModel::where('id', '>', '0');
            if($param['keys'] != '') {
                $list = $list->where('sn|tax_title|tax_no','like','%'.$param['keys'].'%');
            }
            if($param['k3'] != '' && $param['k4'] != ''){
                $list = $list->where('add_date','>=',strtotime($param['k3']))->where('add_date','<=',strtotime($param['k4']));
            }
            $list = $list->order('id','desc')
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
        $list = new BillModel();
        if(isset($param['m_id'])) {
            $list = BillModel::find($param['m_id']);
        }
        try {
            $data = [
                'tax_no' => $param['m_tax_no'],
                'tax_pic' => $param['m_pic'],
                'tax_url' => '',
                'reason' => $param['m_reason'],
                'opt_date' => time(),
                'state' => $param['m_state']
            ];
            $list->save($data);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }
}