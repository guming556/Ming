<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/30
 * Time: 16:08
 */

namespace app\api\model;


class Theme extends BaseModel
{
    public function topicImg() {
        return $this->belongsTo('Image','topic_img_id','id');
    }

    public function headImg() {
        return $this->belongsTo('Image','head_img_id','id');
    }

    public function products() {
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    public static function getThemeWithProduct($id) {
        $theme =  self::with('products,topicImg,headImg')->find($id);
        return $theme;
    }
}