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

use app\common\model\User as UserModel;
use app\common\model\UserLevel as UserLevelModel;
use app\common\model\UserLabel as UserLabelModel;
use think\facade\Db;

class User extends Base
{
    /**
     * 读取数据
     * @param int $id
     * @return UserModel|array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        $data = UserModel::find($id);
        if(!empty($data)) {
            $otherUser = '';
            if($data['wechat_user_id'] > 0) {
                $wechatUser = Db::name('wechat_user')->where('id',$data['wechat_user_id'])->find();
                if(!empty($wechatUser)) {
                    $otherUser = $otherUser . '公众号：' . $wechatUser['openid'] . '/' . $wechatUser['nickname'];
                }
            }
            if($data['miniapp_user_id'] > 0) {
                $miniappUser = Db::name('miniapp_user')->where('id',$data['miniapp_user_id'])->find();
                if(!empty($miniappUser)) {
                    $otherUser = $otherUser . '小程序：' . $miniappUser['openid'] . '/' . $miniappUser['nickname'];
                }
            }
            $data['other_user'] = $otherUser;
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
            $list = UserModel::where('id', '>', '0');
            if($param['keys'] != '') {
                $list = $list->where('uid|mobile|nickname','like','%'.$param['keys'].'%');
            }
            if(!empty($param['k2'])) {
                $list = $list->whereFindInSet('label_id',$param['k2']);
            }
            if(!empty($param['k3'])) {
                $list = $list->where('level_id',$param['k3']);
            }
            if(isset($param['m_id']) && !empty($param['m_id'])) {
                $list = $list->where('id','in',$param['m_id']);
            }
            $list = $list->order(['id'=>'desc'])
                ->paginate($param['limit'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key]['source'] = '用户注册';
                if(!empty($value['wechat_user_id'])) $list['data'][$key]['source'] = '公众号';
                if(!empty($value['miniapp_user_id'])) $list['data'][$key]['source'] = '小程序';
                if(!empty($value['qq_user_id'])) $list['data'][$key]['source'] = 'QQ登录';
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
        $list = new UserModel();
        if(isset($param['m_id'])) {
            //编辑
            $list = UserModel::find($param['m_id']);
            $data = [
                'nickname' => $param['m_nickname'],
                'email' => $param['m_email'],
                'mobile' => $param['m_tel'],
                'level_id' => $param['m_level'],
                'label_id' => $param['m_label'],
                'is_work' => $param['m_work'],
                'is_fx' => $param['m_fx']
            ];
        } else {
            //添加
            if(isInvalidUid($param['m_uid'])) return fail('此账号不允许注册');
            $user = UserModel::where('uid',$param['m_uid'])->find();
            if (!empty($user)) return fail('此账号已存在');
            $data = [
                'uuid' => makeUuid(),
                'uid' => $param['m_uid'],
                'pwd' => $param['m_pwd'],
                'level_id' => $param['m_level'],
                'add_date' => time(),
                'is_work' => 1
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
        $str = '';
        try {
            foreach ($ids as $id) {
                $data = UserModel::where('id',$id)->find();
                if(!empty($data)) {
                    //订单
                    $order = Db::name('order')->where('user_id',$id)->find();
                    if(!empty($order)) {
                        $str = $str . $id . ',';
                        continue;
                    }
                    //积分
                    $integral = Db::name('integral_detail')->where('user_id',$id)->find();
                    if(!empty($integral)) {
                        $str = $str . $id . ',';
                        continue;
                    }
                    //成长值
                    $growth = Db::name('growth_detail')->where('user_id',$id)->find();
                    if(!empty($growth)) {
                        $str = $str . $id . ',';
                        continue;
                    }
                    //余额
                    $balance = Db::name('balance_detail')->where('user_id',$id)->find();
                    if(!empty($balance)) {
                        $str = $str . $id . ',';
                        continue;
                    }
                    //优惠券
                    $coupon = Db::name('coupon_user')->where('user_id', $id)->find();
                    if (!empty($coupon)) {
                        $str = $str . $id . ',';
                        continue;
                    }
                    //执行删除
                    Db::name('user_address')->where('user_id', $id)->delete();
                    Db::name('collect')->where('user_id', $id)->delete();
                    Db::name('cart')->where('user_id', $id)->delete();
                    Db::name('mark')->where('user_id', $id)->delete();
                    $data->delete();
                }
            }
            if(!empty($str)){
                $str = substr($str,0,strlen($str)-1);
                $str = $str . '下有关联信息，不可删除！';
            }
            return success([],$str);
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

    /**
     * 获取等级列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLevelList() : array
    {
        return UserLevelModel::where('id','>','0')
            ->order('sequence')
            ->select()
            ->toArray();
    }

    /**
     * 获取标签列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLabelList() : array
    {
        return UserLabelModel::where('id','>','0')
            ->order('sequence')
            ->select()
            ->toArray();
    }

    /**
     * 保存密码修改
     * @param $param
     * @return array
     */
    public function savePwd($param) : array
    {
        try {
            UserModel::update(['pwd' => $param['password']],['id' => $param['m_uid']]);
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }

}