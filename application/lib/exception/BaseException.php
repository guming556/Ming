<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/27
 * Time: 0:31
 */

namespace app\lib\exception;


use think\Exception;
use Throwable;

class BaseException extends Exception
{
    //http状态码
    public $code;
    //错误的具体信息
    public $msg;
    //自定义的错误码
    public $errorCode;

    public function __construct($params = [])
    {
        if (!is_array($params)) {
            return;
        }
        if (array_key_exists('code',$params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('msg',$params)) {
            $this->msg = $params['msg'];
        }
        if (array_key_exists('errorCode',$params)) {
            $this->errorCode = $params['errorCode'];
        }
    }

}