<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/31
 * Time: 0:07
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 404;
    public $msg = '请求的类目不存在，请检查参数';
    public $errorCode = 50000;
}