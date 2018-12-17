<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/14
 * Time: 0:15
 */

namespace app\api\model;


class ProductImage extends BaseModel
{

    public function imgUrl() {
        return $this->belongsTo('Image','img_id','id');
    }
}