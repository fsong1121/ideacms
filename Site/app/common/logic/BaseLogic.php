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

namespace app\common\logic;

use think\facade\Cache;
use think\facade\Db;

class BaseLogic
{
    /**
     * 获取省市县
     * @param int $pid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAreaList(int $pid = 0) : array
    {
        if(Cache::has('area' . $pid)) {
            $list = Cache::get('area' . $pid);
        }
        else {
            $list = Db::name('area')
                ->where('parent_id',$pid)
                ->where('status',1)
                ->select()
                ->toArray();
            Cache::tag('area')->set('area' . $pid,$list);
        }
        return $list;
    }

    /**
     * 更新token列表缓存
     * @param string $role
     */
    public function setToken(string $role = 'user')
    {
        $time = time();
        $tokenList = Db::name('token')
            ->where('type',$role)
            ->where('create_time','<=',$time)
            ->where('expires_time','>=',$time)
            ->column('access_token');
        Cache::tag('token')->set($role . '_token',$tokenList);
    }

    /**
     * 更新分类列表缓存(可用于更新商品分类，文章分类，后台菜单等列表)
     * @param string $table
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setCat(string $table = 'goods_category')
    {
        $orderType = $table == 'admin_menu' ? 'asc' : 'desc';
        $list = Db::name($table)
            ->where('id', '>',0)
            ->order(['sequence' => $orderType,'id' => 'desc'])
            ->select()
            ->toArray();
        foreach ($list as $key => $value) {
            $list[$key]['child_count'] = Db::name($table)->where('parent_id',$value['id'])->count();
            if($table == 'admin_menu') {
                $list[$key]['subtitle'] = empty($value['subtitle']) ? '' : $value['subtitle'];
                $list[$key]['controller'] = empty($value['controller']) ? '' : $value['controller'];
                $list[$key]['operation'] = empty($value['operation']) ? '' : $value['operation'];
                $list[$key]['ico'] = empty($value['ico']) ? '' : $value['ico'];
            }
        }

        Cache::tag('category')->set($table, $list);
    }

    /**
     * 获取分类列表(可用于获取商品分类，文章分类，后台菜单等列表)
     * @param int $parentId  上级ID
     * @param string $table  表名
     * @param int $scene     0:后台 1:前台
     * @param int $all       0:只调用1级 1:递归所有
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCat(int $parentId = 0, string $table = 'goods_category', int $scene = 0, int $all = 1) : array
    {
        $list = Cache::get($table);
        if(empty($list)){
            $this->setCat($table);
        }
        return $this->newCat(Cache::get($table),$parentId,$scene,$all);
    }

    /**
     * 重构分类列表
     * @param array $list
     * @param int $parentId
     * @param int $scene
     * @param int $all
     * @return array
     */
    private function newCat(array $list = [],int $parentId = 0, int $scene = 0, int $all = 1) : array
    {
        if (empty($list)) return $list;
        static $newList;
        if($all == 0) $newList = [];
        foreach ($list as $key => $value){
            if (($scene == 0 && $value['parent_id'] == $parentId) || ($scene == 1 && $value['parent_id'] == $parentId && $value['is_show'] == 1)) {
                if(array_key_exists('pic', $value)) $value['pic'] = getPic($value['pic']);
                $newList[] = $value;
                if($all) $this->newCat($list,$value['id'],$scene);
            }
        }
        return empty($newList) ? [] : $newList;
    }

    /**
     * 更新用户账户信息
     * @param int $user_id 会员ID
     * @param float $num 数量(正数为加，负数为减)
     * @param int $type 0:积分 1:余额 2:佣金 3:成长值
     * @param string $info 说明
     * @param int $commission_type 佣金类型 0:提现 1:订单
     * @return array
     */
    static public function saveUserAccount(int $user_id = 0,float $num = 0, int $type = 0, string $info = '', int $commission_type = 0) : array
    {
        // 启动事务
        Db::startTrans();
        try {
            $sn = '';
            $user = Db::name('user')->where('id',$user_id)->find();
            if(!empty($num) && !empty($user)) {
                switch ($type) {
                    case 0:
                        //积分
                        $newIntegral = $user['integral'] + $num;
                        if($newIntegral < 0) {
                            return fail('积分不足');
                        }
                        $res = Db::name('user')
                            ->where('id',$user_id)
                            ->where('version',$user['version'])
                            ->inc('version')
                            ->update(['integral' => $newIntegral]);
                        if($res > 0) {
                            $sn = makeOrderSn('I');
                            $data = [
                                'sn' => $sn,
                                'user_id' => $user_id,
                                'fee' => $num,
                                'info' => $info,
                                'account_fee' => $newIntegral,
                                'add_date' => time()
                            ];
                            Db::name('integral_detail')->insert($data);
                        } else {
                            self::saveUserAccount($user_id,$num,$type,$info);
                        }
                        break;
                    case 1:
                        //余额
                        $newBalance = $user['balance'] + $num;
                        if($newBalance < 0) {
                            return fail('余额不足');
                        }
                        $res = Db::name('user')
                            ->where('id',$user_id)
                            ->where('version',$user['version'])
                            ->inc('version')
                            ->update(['balance' => $newBalance]);
                        if($res > 0) {
                            $sn = makeOrderSn('B');
                            $data = [
                                'sn' => $sn,
                                'user_id' => $user_id,
                                'fee' => $num,
                                'info' => $info,
                                'account_fee' => $newBalance,
                                'add_date' => time()
                            ];
                            Db::name('balance_detail')->insert($data);
                        } else {
                            self::saveUserAccount($user_id,$num,$type,$info);
                        }
                        break;
                    case 2:
                        $newCommission = $user['commission'] + $num;
                        if($newCommission < 0) {
                            return fail('佣金不足');
                        }
                        $res = Db::name('user')
                            ->where('id',$user_id)
                            ->where('version',$user['version'])
                            ->inc('version')
                            ->update(['commission' => $newCommission]);
                        if($res > 0) {
                            $sn = makeOrderSn('C');
                            $data = [
                                'sn' => $sn,
                                'user_id' => $user_id,
                                'fee' => $num,
                                'info' => $info,
                                'account_fee' => $newCommission,
                                'type' => $commission_type,
                                'add_date' => time()
                            ];
                            Db::name('commission_detail')->insert($data);
                        } else {
                            self::saveUserAccount($user_id,$num,$type,$info);
                        }
                        break;
                    case 3:
                        $newGrowth = $user['growth'] + $num;
                        if($newGrowth < 0) {
                            return fail('成长值不足');
                        }
                        $res = Db::name('user')
                            ->where('id',$user_id)
                            ->where('version',$user['version'])
                            ->inc('version')
                            ->update(['growth' => $newGrowth]);
                        if($res > 0) {
                            $sn = makeOrderSn('G');
                            $data = [
                                'sn' => $sn,
                                'user_id' => $user_id,
                                'fee' => $num,
                                'info' => $info,
                                'account_fee' => $newGrowth,
                                'add_date' => time()
                            ];
                            Db::name('growth_detail')->insert($data);
                        } else {
                            self::saveUserAccount($user_id,$num,$type,$info);
                        }
                        break;
                }
                // 提交事务
                Db::commit();
                return success(['sn' => $sn]);
            } else {
                return fail('会员不存在');
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return fail($e->getMessage());
        }
    }

}