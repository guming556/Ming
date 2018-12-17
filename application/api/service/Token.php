<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/11
 * Time: 18:00
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken() {
        $randChars = getRandChars(64);
        $timestamp =  $_SERVER['REQUEST_TIME'];
        return md5(md5($randChars).$timestamp);
    }

    public static function getCurrentTokenVar($key) {
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if(!$vars) {
            throw new TokenException();
        }else {
            if (!is_array($vars)) {
                $vars = json_decode($vars,true);
            }
            if (array_key_exists($key,$vars)) {
                return $vars[$key];
            }else {
                throw new Exception('尝试获取的token变量不存在');
            }
        }
    }

    public static function getCurrentUid() {
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    public static function needPrimaryScope() {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope >= ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        }else {
            throw new TokenException();
        }
    }

    public static function needExclusiveScope() {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope = ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        }else {
            throw new TokenException();
        }
    }

    public static function isValidOperate($checkUID) {
        if (!$checkUID) {
            throw new Exception('被检测uid不可为空');
        }
        $currentOperateUID = self::getCurrentUid();
        if ($currentOperateUID == $checkUID) {
            return true;
        }else {
            return false;
        }
    }

    public static function verifyToken($token) {
        $exist = Cache::get($token);
        if ($exist) {
            return true;
        } else {
            return false;
        }
    }
}