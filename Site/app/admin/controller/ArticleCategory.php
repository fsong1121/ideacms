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

use app\common\logic\admin\Article as ArticleLogic;
use app\common\validate\admin\Category as CategoryValidate;
use think\facade\Request;
use think\response\Json;

class ArticleCategory extends Base
{
    /**
     * 列表
     * @return string
     */
    public function index() : string
    {
        return $this->fetch('/article_category');
    }

    /**
     * 添加
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function create() : string
    {
        $logic = new ArticleLogic();
        $this->assign('catList', $logic->getCat(0,'article_category',0,0));
        return $this->fetch('/article_category_add');
    }

    /**
     * 编辑
     * @param int $id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(int $id = 0) : string
    {
        $logic = new ArticleLogic();
        $data = $logic->readCatData($id);
        if (!empty($data)) {
            $this->assign('catList', $logic->getCat(0,'article_category',0,0));
            $this->assign('data', $data);
            return $this->fetch('/article_category_edit');
        }
        else {
            return $this->fetch('statics/404.html');
        }
    }

    /**
     * 获取列表
     */
    public function getList() : Json
    {
        try {
            $logic = new ArticleLogic();
            $list = $logic->getCat(0,'article_category');
            $data = [
                'code' => 0,
                'msg' => '',
                'count' => count($list),
                'data' => $list
            ];
            return json($data);
        } catch (\Exception $e) {
            return json(fail($e->getMessage()));
        }
    }

    /**
     * 保存
     */
    public function save() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new CategoryValidate();
            $result = $validate->scene('article')->check($param);
            if (!$result) {
                return json(fail($validate->getError()));
            } else {
                $logic = new ArticleLogic();
                $data = $logic->saveCatData($param);
                return json($data);
            }
        } else {
            return json(fail('非法提交被禁止'));
        }
    }

    /**
     * 删除
     */
    public function delete() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $id = Request::has('m_id') ? Request::param('m_id') : '0';
            $logic = new ArticleLogic();
            $data = $logic->delCatData($id);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }
}