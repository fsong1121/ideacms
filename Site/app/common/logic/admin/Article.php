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

use app\common\model\ArticleCategory as ArticleCategoryModel;
use app\common\model\Article as ArticleModel;

class Article extends Base
{
    /**
     * 读取分类数据
     * @param int $id
     * @return ArticleCategoryModel|array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readCatData(int $id = 0)
    {
        return ArticleCategoryModel::find($id);
    }

    /**
     * 保存分类数据
     * @param $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveCatData($param) : array
    {
        $list = new ArticleCategoryModel();
        if(isset($param['m_id'])) {
            $list = ArticleCategoryModel::find($param['m_id']);
        }
        try {
            $data = [
                'parent_id' => $param['m_parent_id'],
                'title' => $param['m_title'],
                'wap_title' => $param['m_wap_title'],
                'info' => $param['m_info'],
                'keywords' => $param['m_keys'],
                'description' => $param['m_des'],
                'sequence' => $param['m_px'],
                'is_top' => $param['m_top'],
                'is_show' => $param['m_show']
            ];
            $list->save($data);
            $this->setCat('article_category');
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 删除分类
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delCatData($id) : array
    {
        $data = ArticleCategoryModel::where('id',$id)->findOrEmpty();
        if(!$data->isEmpty()) {
            $article = ArticleModel::where('cat_id',$id)->findOrEmpty();
            if(!$article->isEmpty()) {
                return fail('分类下有文章,不能删除!');
            } else {
                $sonList = ArticleCategoryModel::where('parent_id',$id)->findOrEmpty();
                if(!$sonList->isEmpty()) {
                    return fail( '分类下有子栏目,不能删除!');
                } else {
                    $data->delete();
                    $this->setCat('article_category');
                }
            }
        }
        return success();
    }

    /**
     * 读取数据
     * @param int $id
     * @return ArticleModel|array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function readData(int $id = 0)
    {
        return ArticleModel::find($id);
    }

    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = ArticleModel::where('id', '>', '0');
            if($param['keys'] != '') {
                $list = $list->where('title','like','%'.$param['keys'].'%');
            }
            if($param['k2'] != '') {
                $list = $list->where('cat_id',$param['k2']);
            }
            $list = $list->order(['sequence'=>'desc','id'=>'desc'])
                ->paginate($param['limit'])
                ->toArray();
            return [
                'code' => 0,
                'msg' => '',
                'count' => $list['total'],
                'data' => $list['data']
            ];
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 保存数据
     * @param $param
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveData($param) : array
    {
        $list = new ArticleModel();
        if(isset($param['m_id'])) {
            $list = ArticleModel::find($param['m_id']);
        }
        try {
            $data = [
                'title' => $param['m_title'],
                'cat_id' => $param['m_cat'],
                'source' => $param['m_source'],
                'pic' => $param['m_pic'],
                'summary' => $param['m_summary'],
                'tags' => $param['m_tags'],
                'info' => $param['m_info'],
                'url' => $param['m_url'],
                'hits' => $param['m_hits'],
                'zan' => $param['m_zan'],
                'sequence' => $param['m_px'],
                'is_top' => $param['m_top'],
                'is_show' => $param['m_show'],
                'keywords' => $param['m_keys'],
                'description' => $param['m_des']
            ];
            //如果是新增
            if(!isset($param['m_id'])) {
                $data['add_date'] = time();
            }
            $list->save($data);
            return success();
        }
        catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }

    /**
     * 删除
     * @param $ids
     * @return array
     */
    public function delData($ids) : array
    {
        $ids = explode(",", $ids);
        try {
            ArticleModel::destroy($ids);
            return success();
        }
        catch (\Exception $e){
            return fail($e->getMessage());
        }
    }


}