<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/27
 * Time: 16:50
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的Banner不存在';
    public $errorCode = 40000;
}