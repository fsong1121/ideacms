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
namespace app\common\logic\index;

use app\common\logic\BaseLogic;
use app\common\model\Article as ArticleModel;
use think\facade\Db;

class Article extends BaseLogic
{
    /**
     * 读取数据
     * @param array $param
     * @return array
     */
    public function readData(array $param) : array
    {
        try {
            $res = ['code' => 0,'msg' => 'success'];
            $data = ArticleModel::where('id',$param['id'])
                ->where('is_show',1)
                ->find();
            if(!empty($data)) {
                $data->save(['hits'=>$data['hits']+1]);
                $data['y_cat_id'] = $data->getData('cat_id');
                $res['data'] = $data;
            } else {
                $res = fail('文章不存在');
            }
            return $res;
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }
    /**
     * 获取列表
     * @param array $param
     * @return array
     */
    public function readList(array $param = []) : array
    {
        try {
            $list = ArticleModel::where('is_show', 1);
            if($param['keys'] != '') {
                $list = $list->where('title','like','%'.$param['keys'].'%');
            }
            //所属ID
            if (!empty($param['id'])) {
                $list = $list->where('id','in',$param['id']);
            }
            //所属分类
            if (!empty($param['cat'])) {
                $childCat = Db::name('article_category')->where('parent_id',$param['cat'])->column('id');
                array_push($childCat,$param['cat']);
                $list = $list->where('cat_id','in',$childCat);
            }
            //字段
            if (!empty($param['field'])) {
                $list = $list->field($param['field']);
            }
            $list = $list->order(['is_top'=>'desc','sequence'=>'desc','id'=>'desc'])
                ->paginate($param['size'])
                ->toArray();
            foreach ($list['data'] as $key => $value) {
                if(isset($value['info'])) {
                    $list['data'][$key]['survey'] = str_replace("&nbsp;", "", strip_tags($value['info']));
                }
            }
            $data = [
                'code' => 0,
                'msg' => 'success',
                'count' => $list['total'],
                'data' => $list['data']
            ];
            if (!empty($param['cat'])) {
                $data['cat_title'] = Db::name('article_category')
                    ->where('id',$param['cat'])
                    ->value('title');
            }
            return $data;
        } catch (\Exception $e) {
            return fail($e->getMessage());
        }
    }
}