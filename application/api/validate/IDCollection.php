<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/30
 * Time: 16:30
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIDs'
    ];

    protected $message = [
        'ids' => 'ids必须是以逗号分隔的多个正整数'
    ];

    protected function checkIDs($value) {
        $valus = explode(',',$value);
        if (empty($valus)) {
            return false;
        }
        foreach ($valus as $id) {
            if (!($this->isPositiveInteger($id))) {
                return false;
            }
        }
        return true;
    }
}