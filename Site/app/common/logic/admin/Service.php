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

use app\common\model\Service as ServiceModel;

class Service extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return ServiceModel|array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        return ServiceModel::find($id);
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = ServiceModel::where('id', '>', '0');
            if($param['keys'] != '') {
                $list = $list->where('title','like','%'.$param['keys'].'%');
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
        $list = new ServiceModel();
        if(isset($param['m_id'])) {
            $list = ServiceModel::find($param['m_id']);
        }
        try {
            $data = [
                'title' => $param['m_title'],
                'info' => $param['m_info'],
                'sequence' => $param['m_px'],
                'is_show' => $param['m_show']
            ];
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
            ServiceModel::destroy($ids);
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }
}