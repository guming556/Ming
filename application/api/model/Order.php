<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/20
 * Time: 14:17
 */

namespace app\api\model;


class Order extends BaseModel
{

    public function getSnapItemsAttr($value) {
        if (empty($value)) {
            return null;
        }
        return json_decode($value);
    }

    public function getSnapAddressAttr($value) {
        if (empty($value)) {
            return null;
        }
        return json_decode($value);
    }

    /**
     * @param $uid
     * @param $page
     * @param $size
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public static function getSummaryByUser($uid,$page,$size) {
        $pagingData = self::where('user_id','=',$uid)->order('create_time desc')
            ->paginate($size,true,['page' => $page]);
        return $pagingData;
    }

    /**
     * @param $page
     * @param $size
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public static function getSummaryByPage($page,$size) {
        $pagingData = self::order('create_time desc')
            ->paginate($size,true,['page' => $page]);
        return $pagingData;
    }

}