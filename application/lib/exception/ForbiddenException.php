<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/17
 * Time: 23:15
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不够';
    public $errorCode = 10003;
}