<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/30
 * Time: 22:51
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\CategoryException;
use app\lib\exception\ProductException;

class Product
{
    /**
     * @param int $count
     * @return \think\response\Json
     * @throws ProductException
     * @throws \app\lib\exception\ParamterException
     */
    public function getRecent($count = 10) {
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if ($products->isEmpty()) {
            throw new ProductException();
        }
        return json($products);
    }

    /**
     * @param $id
     * @return \think\response\Json
     * @throws CategoryException
     * @throws \app\lib\exception\ParamterException
     */
    public static function getAllByCategory($id) {
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryID($id);
        if ($products->isEmpty()) {
            throw new CategoryException();
        }
        return json($products);
    }

    public function getOne($id) {
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if (!$product) {
            throw new ProductException();
        }
        return json($product);

    }
}