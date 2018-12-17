<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/16
 * Time: 15:50
 */

namespace app\api\model;


class ThirdApp extends BaseModel
{
    /**
     * @param $ac
     * @param $sc
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function check($ac,$sc) {
        $app = self::where('app_id','=',$ac)
            ->where('app_secret','=',$sc)
            ->find();
        return $app;
    }
}