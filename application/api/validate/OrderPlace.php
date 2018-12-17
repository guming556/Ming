<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/19
 * Time: 15:12
 */

namespace app\api\validate;


use app\lib\exception\ParamterException;

class OrderPlace extends BaseValidate
{
    protected $rule = [
        'products' => 'checkProducts'
    ];
    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger'
    ];

    /**
     * @param $values
     * @return bool
     * @throws ParamterException
     */
    protected function checkProducts($values) {
        if (empty($values)) {
            throw new ParamterException([
                'msg' => '商品列表不能为空'
            ]);
        }
        if (!is_array($values)) {
            throw new ParamterException([
                'msg' => '商品参数不正确'
            ]);
        }
        foreach ($values as $value) {
            $this->checkProduct($value);
        }
        return true;
    }

    /**
     * @param $value
     * @throws ParamterException
     */
    protected function checkProduct($value) {
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if (!$result) {
            throw new ParamterException([
                'msg' => '商品列表参数错误'
            ]);
        }
    }
}