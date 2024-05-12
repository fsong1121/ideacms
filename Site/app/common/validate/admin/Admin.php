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

namespace app\common\validate\admin;

use think\facade\Db;
use think\Validate;

class Admin extends Validate
{
    protected $rule =   [
        'm_uid'  => 'require|checkName',
        'm_pwd'   => 'checkPassword',
    ];

    protected $message  =   [
        'm_uid.require' => '用户名为空',
    ];

    /**
     * 验证用户名是否已存在
     * @param $value
     * @param $data
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function checkName($value,$rule,$data=[])
    {
        if(empty($data['m_id'])){//添加
            $list = Db::name('admin')->where('uid',$value)->find();
            if(!empty($list)){
                return '账号已存在';
            }
        }
        else {//编辑
            $list = Db::name('admin')->where('uid',$value)->find();
            if(!empty($list) && $data['m_id'] != $list['id']){
                return '账号已存在';
            }
        }
        return true;
    }

    /**
     * 编辑的时候验证二次密码
     * @param $value
     * @param $data
     * @return bool|string
     */
    protected function checkPassword($value,$rule,$data=[])
    {
        if(empty($data['m_id'])){ //添加
            if(empty($value)){
                return '密码为空';
            }
        }
        else {  //编辑
            if(!empty($value) && $value != $data['m_vpwd']) {
                return '两次密码不一致';
            }
        }
        return true;
    }
}