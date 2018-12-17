<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/16
 * Time: 15:38
 */

namespace app\api\service;


use app\api\model\ThirdApp;
use app\lib\exception\TokenException;

class AppToken extends Token
{
    /**
     * @param $ac
     * @param $se
     * @return string
     * @throws TokenException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get($ac,$se) {
        $app = ThirdApp::check($ac,$se);
        if (!$app) {
            throw new TokenException([
                'msg' => '授权失败',
                'errorCode' => 10004
            ]);
        } else {
            $scope = intval($app->scope);
            $uid = $app->id;
            $values = [
                'scope' => $scope,
                'uid' => $uid
            ];
            $token = $this->saveToCache($values);
            return $token;
        }
    }

    /**
     * @param $values
     * @return string
     * @throws TokenException
     */
    private function saveToCache($values){
        $token = self::generateToken();
        $expire_in = config('setting.token_expire_in');
        $result = cache($token,json_encode($values),$expire_in);
        if (!$result) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $token;
    }
}