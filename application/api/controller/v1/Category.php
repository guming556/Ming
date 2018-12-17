<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/30
 * Time: 23:51
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category extends BaseController
{
    /**
     * @return \think\response\Json
     * @throws CategoryException
     * @throws \think\exception\DbException
     */
    public function getAllCategories() {
        $categories = CategoryModel::all([],'img');
        if (!$categories) {
            throw  new CategoryException();
        }
        return json($categories);
    }
}