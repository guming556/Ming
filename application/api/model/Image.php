<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/29
 * Time: 14:29
 */

namespace app\api\model;


class Image extends BaseModel
{
    public function getUrlAttr($value,$data) {
        return $this->prefixImgUrl($value,$data);
    }
}