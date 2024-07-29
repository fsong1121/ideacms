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
namespace app\common\logic\index;

use app\common\logic\BaseLogic;
use app\common\model\User as UserModel;
use think\facade\Event;
use think\facade\Db;

class User extends BaseLogic
{
    /**
     * 读取数据
     * @param array $param
     * @return array
     */
    public function readData(array $param) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $time = time();
            $data = UserModel::where('id',$param['user_id'])
                ->where('is_work',1)
                ->withoutField('pwd,pay_pwd,last_login_time,last_login_ip,login_times,version,add_date')
                ->find();
            if(!empty($data)) {
                $data['avatar'] = empty($data['avatar']) ? '' : $data['avatar'];
                $data['avatar'] = getPic($data['avatar'],1);
                $data['couponNumber'] = Db::name('coupon_user')
                    ->where('user_id',$param['user_id'])
                    ->where('is_use',0)
                    ->where('add_date','<=',$time)
                    ->where('end_date','>=',$time)
                    ->count();
                //分销
                $distribution = Event::trigger('getUserDistributionInfo',[
                    'user_id' => $data['id']
                ]);
                if(!empty($distribution) && $distribution[0]['code'] == 0) {
                    $data['commission_teams'] = $distribution[0]['data']['commission_teams'];
                    $data['commission_orders'] = $distribution[0]['data']['commission_orders'];
                    $data['commission_total'] = $distribution[0]['data']['commission_total'];
                    $data['is_fx'] = $distribution[0]['data']['is_fx'];
                } else {
                    $data['is_fx'] = 0;
                }
                $res['data'] = $data;
            } else {
                $res['code'] = 500;
                $res['msg'] = '会员不存在或已被禁用';
            }
            return $res;
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存会员信息
     * @param array $param
     * @return array
     */
    public function saveInfo(array $param) : array
    {
        try {
            $data = [];
            if(isset($param['name'])) {
                $data['nickname'] = $param['name'];
            }
            if(isset($param['tel'])) {
                $data['mobile'] = $param['tel'];
            }
            if(isset($param['avatar'])) {
                $data['avatar'] = $param['avatar'];
            }
            UserModel::update($data,['id'=>$param['user_id']]);
            return ['code' => 0,'msg' => 'success'];
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存密码
     * @param array $param
     * @return array
     */
    public function savePwd(array $param) : array
    {
        try {
            UserModel::update(['pwd'=>$param['pwd']],['id'=>$param['user_id']]);
            return ['code' => 0,'msg' => 'success'];
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

}