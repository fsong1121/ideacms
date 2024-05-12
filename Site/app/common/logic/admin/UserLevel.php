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

use app\common\model\UserLevel as UserLevelModel;
use think\facade\Db;

class UserLevel extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return UserLevelModel|array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        return UserLevelModel::find($id);
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = UserLevelModel::where('id', '>', '0');
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
        $list = new UserLevelModel();
        if(isset($param['m_id'])) {
            $list = UserLevelModel::find($param['m_id']);
        }
        try {
            $data = [
                'title' => $param['m_title'],
                'growth' => $param['m_growth'],
                'rebate' => $param['m_rebate'],
                'info' => $param['m_info'],
                'sequence' => $param['m_px']
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
        $str = '';
        try {
            foreach ($ids as $id) {
                $data = UserLevelModel::where('id',$id)->findOrEmpty();
                if(!$data->isEmpty()) {
                    $user = Db::name('user')->where('level_id',$id)->findOrEmpty();
                    if(!empty($user)) {
                        $str = $str . $id . ',';
                    } else {
                        $data->delete();
                    }
                }
            }
            if(!empty($str)){
                $str = substr($str,0,strlen($str)-1);
                $str = $str . '下有会员，不可删除！';
            }
            return success([],$str);
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }
}