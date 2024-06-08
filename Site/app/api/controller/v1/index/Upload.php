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

use Qcloud\Cos\Client;
use OSS\OssClient;
use Qiniu\Storage\UploadManager;
use Qiniu\Auth;
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
        $config = config('upload');
        if($res['code'] == 0) {
            $param = $res['data'];
            if(!empty($param['user_id'])) {
                $file = Request::file('file');
                $type = Request::has('type') ? Request::param('type') : 'pic';
                $dir = Request::has('dir') ? Request::param('dir') : 'goods';
                $files = Request::file();
                try {
                    validate(['file' => 'filesize:' . config('upload.upsize') * 1024 * 1024 . '|fileExt:' . config('upload.uptype') . ''])->check($files);
                    //原始文件后缀名
                    $ext = $file->getOriginalExtension();
                    $path = $type . '/' . $dir . '/' . md5(microtime(true) . mt_rand(1, 1e9)) . '.' . $ext;
                    if($config['uplocation'] == 1) {
                        //上传到本地
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
                    }
                    if($config['uplocation'] == 2) {
                        //上传到七牛云
                        $auth = new Auth($config['qiniu_ak'], $config['qiniu_sk']);
                        // 上传的token
                        $token = $auth->uploadToken($config['qiniu_bucket']);
                        //上传文件
                        $uploadMgr = new UploadManager();
                        $fileName = $file->getRealPath();
                        list($ret, $err) = $uploadMgr->putFile($token, $path, $fileName);
                        if ($err !== null) {
                            $res['code'] = 500;
                            $res['msg'] = $err;
                        } else {
                            $res['data']['file'] = $config['qiniu_domain'] . '/' . $ret['key'];
                            $res['data']['src'] = $config['qiniu_domain'] . '/' . $ret['key'];
                        }
                    }
                    if($config['uplocation'] == 3) {
                        //上传到腾讯云
                        // 初始化 COS 客户端
                        $cosClient = new Client([
                            'region' => $config['qcloud_region'], // 设置COS所在地区
                            'credentials' => [
                                'appId' => $config['qcloud_appId'],
                                'secretId'  => $config['qcloud_secretId'],
                                'secretKey' => $config['qcloud_secretKey'],
                            ],
                        ]);

                        // 要上传的文件
                        $filePath = $file->getPathname(); // 本地文件路径
                        $path = '/' . $path;
                        // 上传到COS
                        try {
                            $cosClient->putObject([
                                'Bucket' => $config['qcloud_bucket'],
                                'Key' => ltrim($path, '/'),
                                'Body' => fopen($filePath, 'rb')
                            ]);

                            // 上传成功，返回文件URL
                            $res['data']['file'] = $config['qcloud_cosUrl'] . $path;
                            $res['data']['src'] = $config['qcloud_cosUrl'] . $path;
                        } catch (\Exception $e) {
                            // 上传失败的处理逻辑
                            $res['code'] = 500;
                            $res['msg'] = $e->getMessage();
                        }
                    }
                    if($config['uplocation'] == 4) {
                        //上传到阿里云
                        // 获取上传文件的路径和文件名
                        $localPath = $file->getPathname();

                        // 获取OSS Bucket中的路径
                        $bucket = $config['ali_bucket'];
                        $ossPath = $path;

                        // 上传文件到OSS
                        try {
                            // 实例化OSSClient
                            $ossClient = new OssClient($config['ali_accessKeyId'], $config['ali_accessKeySecret'], $config['ali_endpoint']);
                            $res1 = $ossClient->uploadFile($bucket, $ossPath, $localPath);
                            // 上传成功，返回文件URL
                            $res['data']['file'] = $res1['info']['url'];
                            $res['data']['src'] = $res1['info']['url'];
                        } catch (\Exception $e) {
                            $res['code'] = 500;
                            $res['msg'] = $e->getMessage();
                        }
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