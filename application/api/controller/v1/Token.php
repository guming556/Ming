<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/31
 * Time: 1:37
 */

namespace app\api\controller\v1;


use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\service\Token as TokenService;
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet;
use app\lib\exception\ParamterException;

class Token
{
    /**
     * @param string $code
     * @return array
     * @throws \app\lib\exception\ParamterException
     * @throws \app\lib\exception\WeChatException
     * @throws \think\Exception
     */
    public function getToken($code = '') {
        (new TokenGet())->goCheck();
        $ut = new UserToken($code);
        $token = $ut->get();
        return json([
            'token' => $token
        ]);
    }

    public function getAppToken($ac = '', $se = '') {
        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $token = $app->get($ac,$se);
        return json([
            'token' => $token
        ]);
    }

    public function verifyToken($token = '') {
        if (!$token) {
            throw new ParamterException(['token不允许为空']);
        }
        $valid = TokenService::verifyToken($token);
        return json([
            'isValid' => $valid
        ]);
    }
}