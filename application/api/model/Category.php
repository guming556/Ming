<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/30
 * Time: 23:53
 */

namespace app\api\model;


class Category extends BaseModel
{
    public function img() {
        return $this->belongsTo('Image','topic_img_id','id');
    }
}