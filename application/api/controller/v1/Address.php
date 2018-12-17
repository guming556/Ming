<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/14
 * Time: 0:52
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\User as UserModel;
use app\api\model\UserAddress;
use app\api\service\Token as TokenService;
use app\api\validate\AddressNew;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;


class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createorupdateaddress,getuseraddress']
    ];

    public function createOrUpdateAddress() {
        $validate = new AddressNew();
        $validate->goCheck();
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user) {
            throw new UserException();
        }
        $dataArray = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;
        if (!$userAddress) {
            $user->address()->save($dataArray);
        }else {
            $user->address->save($dataArray);
        }
        return json(new SuccessMessage(),201);
    }

    public function getUserAddress(){
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddress::where('user_id',$uid)->find();
        if (!$userAddress) {
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode' => 60001
            ]);
        }
        return json($userAddress);
    }
}