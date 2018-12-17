<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/31
 * Time: 1:49
 */

namespace app\api\model;


class User extends BaseModel
{
    public static function getByOpenID($openid) {
        $user = self::where('openid','=',$openid)->find();
        return $user;
    }

    public function address() {
        return $this->hasOne('UserAddress','user_id','id');
    }
}