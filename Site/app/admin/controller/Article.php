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
use app\common\validate\admin\Article as ArticleValidate;
use think\facade\Request;
use think\response\Json;

class Article extends Base
{
    /**
     * 列表
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index() : string
    {
        $logic = new ArticleLogic();
        $this->assign('catList', $logic->getCat(0,'article_category'));
        return $this->fetch('/article');
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
        $this->assign('catList', $logic->getCat(0,'article_category'));
        return $this->fetch('/article_add');
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
        $data = $logic->readData($id);
        if (!empty($data)) {
            $this->assign('catList', $logic->getCat(0,'article_category'));
            $this->assign('data', $data);
            return $this->fetch('/article_edit');
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
        $param = Request::param();
        $perPage = Request::has('limit', 'get') ? $param['limit'] : $this->perPage;
        $keys = trimStr(Request::has('keys','get') ? $param['keys'] : '');
        $k2 = Request::has('k2','get') ? $param['k2'] : '';
        $param['limit'] = $perPage;
        $param['keys'] = $keys;
        $param['k2'] = $k2;
        $logic = new ArticleLogic();
        return json($logic->readList($param));
    }

    /**
     * 保存
     */
    public function save() : Json
    {
        if (Request::isPost() && Request::isAjax()) {
            $param = Request::param();
            $validate = new ArticleValidate();
            $result = $validate->check($param);
            if (!$result) {
                return json(fail( $validate->getError()));
            } else {
                $logic = new ArticleLogic();
                $data = $logic->saveData($param);
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
            $ids = Request::has('m_id') ? Request::param('m_id') : '0';
            $ids = "0," . $ids;
            $logic = new ArticleLogic();
            $data = $logic->delData($ids);
            return json($data);
        } else {
            return json(fail('非法提交被禁止'));
        }
    }
}