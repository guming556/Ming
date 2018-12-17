<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/30
 * Time: 20:29
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = 404;
    public $msg = '指定主题不存在';
    public $errorCode = 30000;
}