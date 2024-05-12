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

class Setting extends Base
{
    /**
     * 保存主题设置
     * @param $param
     * @return array
     */
    public function saveTheme($param) : array
    {
        try {
            $code = "return [
                'theme' => '" . $param['theme'] . "',
                'open_card' => '" . config('setting.open_card') . "',
                'card_pic' => '" . config('setting.card_pic') . "',
                'card_url1' => '" . config('setting.card_url1') . "',
                'card_url2' => '" . config('setting.card_url2') . "',
                'category_type' => '" . config('setting.category_type') . "'
            ]";
            $code = "<?php\n " . $code . ";";
            file_put_contents(config_path() . "setting.php", $code);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存手机端设置
     * @param $param
     * @return array
     */
    public function saveMobile($param) : array
    {
        try {
            $code = "return [
                'theme' => '" . config('setting.theme') . "',
                'open_card' => '" . $param['open_card'] . "',
                'card_pic' => '" . $param['card_pic'] . "',
                'card_url1' => '" . $param['card_url1'] . "',
                'card_url2' => '" . $param['card_url2'] . "',
                'category_type' => '" . $param['category_type'] . "'
            ]";
            $code = "<?php\n " . $code . ";";
            file_put_contents(config_path() . "setting.php", $code);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

}