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

use app\common\logic\index\Article as ArticleLogic;
use think\response\Json;
use think\facade\Request;

class Article extends Base
{
    /**
     * 获取内容
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getData() : Json
    {
        $res = $this->setParam(Request::param(),0);
        if($res['code'] == 0) {
            $param = $res['data'];
            $logic = new ArticleLogic();
            $res = $logic->readData($param);
        }
        return json($res);
    }
    /**
     * 获取列表
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList() : Json
    {
        $res = $this->setParam(Request::param(),0);
        if($res['code'] == 0) {
            $param = $res['data'];
            $param['size'] = $param['size'] ?? $this->perPage;
            $param['keys'] = trimStr($param['keys'] ?? '');
            $param['cat'] = $param['cat'] ?? 0;
            $logic = new ArticleLogic();
            $res = $logic->readList($param);
        }
        return json($res);
    }

    /**
     * 获取文章分类
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCategory() : Json
    {
        $res = $this->setParam(Request::param(),0);
        if($res['code'] == 0) {
            $param = $res['data'];
            $parentId = $param['parent_id'] ?? 0;
            $all = $param['all'] ?? 0;
            $logic = new ArticleLogic();
            return json(success($logic->getCat($parentId, 'article_category', 1, $all)));
        } else {
            return json($res);
        }
    }
}