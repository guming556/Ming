<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/30
 * Time: 22:51
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,12'
    ];
}