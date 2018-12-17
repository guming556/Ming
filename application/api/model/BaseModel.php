<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/27
 * Time: 0:29
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    protected $hidden = ['update_time','delete_time'];

    public function prefixImgUrl($value,$data) {
        $finalUrl = $value;
        if ($data['from'] == 1) {
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }
}