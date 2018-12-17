<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/31
 * Time: 1:37
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];

    protected $message = [
        'code' => 'code不能为空'
    ];
}