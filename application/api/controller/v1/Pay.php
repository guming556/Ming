<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/21
 * Time: 19:43
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;
use think\Loader;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');
class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getpreorder']
    ];

    /**
     * 检测库存量后生成预支付订单
     * @param string $id
     * @return array
     * @throws \WxPayException
     * @throws \app\lib\exception\OrderException
     * @throws \app\lib\exception\ParamterException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getPreOrder($id = '') {
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }

    /**
     * 接收微信服务器的回调通知
     */
    public function receiveNotify(){
        /**
         * 1.检测库存量
         * 2.更新订单的status状态
         * 3.减少商品库存
         */
        $notify = new WxNotify();
        $wxConfig = new \WxPayConfig();
        $notify->Handle($wxConfig);
    }
}