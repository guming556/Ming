<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/14
 * Time: 16:08
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    public $code = 201;
    public $msg = 'ok';
    public $errorCode = 0;
}