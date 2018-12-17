<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/30
 * Time: 16:08
 */

namespace app\api\model;


class Product extends BaseModel
{

    public function imgs() {
        return $this->hasMany('ProductImage','product_id','id');
    }

    public function properties() {
        return $this->hasMany('ProductProperty','product_id','id');
    }

    public function getMainImgUrlAttr($value,$data) {
        return $this->prefixImgUrl($value,$data);
    }

    public static function getMostRecent($count) {
        $products = self::limit($count)->order('create_time','desc')->select();
        return $products;
    }

    public static function getProductsByCategoryID($category_id) {
        $products = self::where('category_id','=',$category_id)->select();
        return $products;
    }

    
    public static function getProductDetail($id) {
        $product = self::with([
            'imgs' => function($query) {
                $query->with('imgUrl')->order('order','asc');
            }
        ])
        ->with(['properties'])->find($id);
        return $product;
    }
}