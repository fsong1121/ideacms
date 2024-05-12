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
namespace app\api\controller\v1\index;

use think\response\Json;
use think\facade\Request;

class Upload extends Base
{
    /**
     * 上传
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index() : Json
    {
        $res = $this->setParam(Request::param());
        if($res['code'] == 0) {
            $param = $res['data'];
            if(!empty($param['user_id'])) {
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
                        $res['data']['file'] = $dir . '/' . $fileName;
                        $res['data']['src'] = request()->domain() . '/upload/' . $type . '/' . $dir . '/' . $fileName;
                    } else {
                        $res['code'] = 500;
                        $res['msg'] = '上传失败！';
                    }
                } catch (\think\exception\ValidateException $e) {
                    $res['code'] = 500;
                    $res['msg'] = $e->getMessage();
                }
            } else {
                $res = fail('请先登录',401);
            }
        }
        return json($res);
    }
}