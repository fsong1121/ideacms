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

namespace app\admin\controller;

use app\common\service\Auth as AuthLogic;
use think\facade\Request;
use think\response\Json;

class Upload
{
    /**
     * 上传
     * @return \think\response\Json
     */
    public function index() : Json
    {
        $auth = new AuthLogic();
        $result = ["code" => 500, "data" => [], "msg" => "请先登录"];
        if($auth->checkLogin('admin')) {
            $result = ["code" => 0, "data" => [], "msg" => "上传成功"];
            $file = Request::file('file');
            $type = Request::has('type') ? Request::param('type') : 'pic';
            $dir = Request::has('dir') ? Request::param('dir') : 'goods';
            $files = Request::file();
            try {
                validate(['file' => 'filesize:' . config('upload.upsize') * 1024 * 1024 . '|fileExt:' . config('upload.uptype') . ''])->check($files);
                $saveName = \think\facade\Filesystem::disk('public')->putFile($type . DIRECTORY_SEPARATOR . $dir, $file, 'uniqid');
                if ($saveName) {
                    $fileArr = explode("/", $saveName);
                    $fileName = end($fileArr);
                    $result['data']['file'] = $dir . '/' . $fileName;
                    $result['data']['src'] = request()->domain() . '/upload/' . $type . '/' . $dir . '/' . $fileName;
                } else {
                    $result['code'] = 500;
                    $result['msg'] = '上传失败！';
                }
            } catch (\think\exception\ValidateException $e) {
                $result['code'] = 500;
                $result['msg'] = $e->getMessage();
            }
        }

        return json($result);
    }
}