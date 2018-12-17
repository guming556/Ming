<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/30
 * Time: 23:19
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    public $code = 404;
    public $msg = '请求的商品不存在';
    public $errorCode = 20000;
}