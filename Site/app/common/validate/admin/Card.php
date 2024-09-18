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

use think\Validate;

class Card extends Validate
{
    protected $rule =   [
        'm_title'  => 'require',
        'm_type'   => 'checkType',
        'm_account'  => 'require',
    ];

    protected $message  =   [
        'm_title.require' => '名称为空',
        'm_account.require' => '卡号为空',
    ];

    /**
     * 判断类型
     * @param $value
     * @param $rule
     * @param array $data
     * @return bool|string
     */
    protected function checkType($value,$rule,$data=[])
    {
        if($value > 0 && empty($data['m_url'])){
            return '卡号或地址为空';
        }
        return true;
    }

    protected $scene = [
        'data' => ['m_title','m_type'],
        'detail' => ['m_account']
    ];
}