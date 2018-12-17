<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/30
 * Time: 16:14
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeException;

class Theme extends BaseController
{
    /**
     * @param string $ids
     * @return \think\response\Json
     * @throws ThemeException
     * @throws \app\lib\exception\ParamterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getSimpleList($ids = '') {
        (new IDCollection())->goCheck();
        $ids = explode(',',$ids);
        $result = ThemeModel::with('topicImg,headImg')->select($ids);
        if (!$result) {
            throw new ThemeException();
        }
        return json($result);
    }

    /**
     * @param $id
     * @return \think\response\Json
     * @throws ThemeException
     * @throws \app\lib\exception\ParamterException
     */
    public function getComplexOne($id) {
        (new IDMustBePositiveInt())->goCheck();
        $result = ThemeModel::getThemeWithProduct($id);
        if (!$result) {
            throw new ThemeException();
        }
        return json($result);
    }
}