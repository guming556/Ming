<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/26
 * Time: 23:49
 */

namespace app\api\controller\v1;


use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\BannerMissException;

class Banner
{
    /**
     * @param $id
     * @return \think\response\Json
     * @throws BannerMissException
     * @throws \app\lib\exception\ParamterException
     */
    public function getBanner($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $banner = BannerModel::getBannerByID($id);
        if (!$banner) {
            throw new BannerMissException();
        }
        return json($banner);
    }
}