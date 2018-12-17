<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/16
 * Time: 15:36
 */

namespace app\api\validate;


class AppTokenGet extends BaseValidate
{
    protected $rule = [
        'ac' => 'require|isNotEmpty',
        'se' => 'require|isNotEmpty'
    ];
}