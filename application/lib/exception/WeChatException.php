<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/4
 * Time: 21:13
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code = 400;
    public $msg = '微信服务器接口调用失败';
    public $errorCode = 999;
}