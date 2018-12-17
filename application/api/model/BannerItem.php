<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/28
 * Time: 16:32
 */

namespace app\api\model;


class BannerItem extends BaseModel
{
    public function img() {
        return $this->belongsTo('Image','img_id','id');
    }
}