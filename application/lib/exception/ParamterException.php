<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/28
 * Time: 14:22
 */

namespace app\lib\exception;


class ParamterException extends BaseException
{
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;
}