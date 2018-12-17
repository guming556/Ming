<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/19
 * Time: 20:38
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单参数错误，请检查相关参数';
    public $errorCode = 80000;
}