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

use app\common\model\ExpressTemplatePrice as ExpressTemplatePriceModel;
use think\facade\Db;

class ExpressTemplatePrice extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return ExpressTemplatePriceModel|array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        return ExpressTemplatePriceModel::find($id);
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $template = Db::name('express_template')->where('id',$param['templateID'])->find();
            if(empty($template)) $param['templateID'] = 0;
            $list = ExpressTemplatePriceModel::where('express_template_id', $param['templateID']);
            $list = $list->order(['id'=>'desc'])
                ->paginate($param['limit'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key]['title'] = $template['title'];
                $list['data'][$key]['type'] = $template['type'];
            }
            return [
                'code' => 0,
                'msg' => '',
                'count' => $list['total'],
                'data' => $list['data']
            ];
        } catch (\Exception $e) {
            return fail( $e->getMessage());
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
        $list = new ExpressTemplatePriceModel();
        if(isset($param['m_id'])) {
            $list = ExpressTemplatePriceModel::find($param['m_id']);
        }
        try {
            $data = [
                'area_names' => $param['m_area'],
                'express_template_id' => $param['m_express_template'],
                'first_num' => $param['m_first_num'],
                'first_price' => $param['m_first_price'],
                'second_num' => $param['m_second_num'],
                'second_price' => $param['m_second_price']
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
            ExpressTemplatePriceModel::destroy($ids);
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 获取已选择区域
     * @param int $template_id
     * @param int $id
     * @return array
     */
    public function getSelectAreaList(int $template_id = 0,int $id = 0) : array
    {
        $areaList = ExpressTemplatePriceModel::where('express_template_id',$template_id);
        if(!empty($id)) {
            $areaList = $areaList->where('id','<>',$id);
        }
        $areaList = $areaList->column('area_names');
        $areaList = implode(",",$areaList);
        return explode(",",$areaList);
    }
}