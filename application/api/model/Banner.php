<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/27
 * Time: 0:28
 */

namespace app\api\model;


class Banner extends BaseModel
{
    public function items() {
        return
            $this->hasMany('BannerItem','banner_id','id');
    }
    public static function getBannerByID($id) {
        $banner = self::with(['items','items.img'])->find($id);
        return $banner;
    }
}