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

use app\common\model\Card as CardModel;
use app\common\model\CardDetail as CardDetailModel;

class Card extends Base
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
        return CardModel::find($id);
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = CardModel::where('id', '>', '0');
            if($param['keys'] != '') {
                $list = $list->where('title','like','%'.$param['keys'].'%');
            }
            $list = $list->order(['id'=>'desc'])
                ->paginate($param['limit'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key]['total'] = 0;
                $list['data'][$key]['get'] = 0;
                if($value['type'] == 0) {
                    $list['data'][$key]['total'] = CardDetailModel::where('card_id',$value['id'])->count();
                    $list['data'][$key]['get'] = CardDetailModel::where('card_id',$value['id'])->where('get_date','>',0)->count();
                }
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
        $list = new CardModel();
        if(isset($param['m_id'])) {
            $list = CardModel::find($param['m_id']);
        }
        try {
            $data = [
                'title' => $param['m_title'],
                'type' => $param['m_type'],
                'url' => $param['m_url'],
                'pwd' => $param['m_pwd']
            ];
            if(!isset($param['m_id'])) {
                $data['add_date'] = time();
            }
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
            CardDetailModel::where('card_id','in',$ids)->delete();
            CardModel::destroy($ids);
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 读取数据
     * @param int $id
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readDetailData(int $id = 0)
    {
        return CardDetailModel::find($id);
    }

    /**
     * 获取卡密列表
     * @param array $param
     * @return array
     */
    public function readDetailList(array $param = []) : array
    {
        try {
            $list = CardDetailModel::where('card_id', $param['card_id']);
            if($param['keys'] != '') {
                $list = $list->where('account','like','%'.$param['keys'].'%');
            }
            if($param['k2'] == 1) {
                //未发放
                $list = $list->where('get_date',0);
            }
            if($param['k2'] == 2) {
                //已发放
                $list = $list->where('get_date','>',0);
            }
            $list = $list->order(['id'=>'desc'])
                ->paginate($param['limit'])
                ->toArray();
            return [
                'code' => 0,
                'msg' => '',
                'count' => $list['total'],
                'data' => $list['data'],
                'param' => $param
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
    public function saveDetailData($param) : array
    {
        $list = new CardDetailModel();
        if(isset($param['m_id'])) {
            $list = CardDetailModel::find($param['m_id']);
        }
        try {
            if(isset($param['m_id'])) {
                $data = [
                    'account' => $param['m_account'],
                    'pwd' => $param['m_pwd']
                ];
                $list->save($data);
            } else {
                $data = [];
                $accountArr = explode(',',$param['m_account']);
                $pwdArr = explode(',',$param['m_pwd']);
                foreach ($accountArr as $key => $value) {
                    $data[$key] = [
                        'card_id' => $param['cart_id'],
                        'account' => $value,
                        'pwd' => $pwdArr[$key],
                        'add_date' => time()
                    ];
                }
                $list->saveAll($data);
            }
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
    public function delDetailData($ids) : array
    {
        $ids = explode(",", $ids);
        try {
            CardDetailModel::destroy($ids);
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }
}