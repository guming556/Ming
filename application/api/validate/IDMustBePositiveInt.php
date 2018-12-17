<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/27
 * Time: 0:33
 */

namespace app\api\validate;


class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    protected $message = [
        'id' => 'id必须是正整数'
    ];
}