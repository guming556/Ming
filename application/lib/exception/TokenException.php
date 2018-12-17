<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/12
 * Time: 14:07
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token无效或Token已过期';
    public $errorCode = 10001;
}