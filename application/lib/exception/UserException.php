<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/14
 * Time: 15:53
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '用户不存在';
    public $errorCode = 60000;
}