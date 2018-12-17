<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/21
 * Time: 19:48
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    private $orderID;
    private $orderNo;

    function __construct($orderID)
    {
        if (!$orderID) {
            throw new Exception('订单号不允许为空');
        }
        $this->orderID = $orderID;
    }

    /**
     * @return array
     * @throws Exception
     * @throws OrderException
     * @throws TokenException
     * @throws \WxPayException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function pay() {
        //进行库存量检测
        $this->checkOrderValidate();
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if (!$status['pass']) {
            return $status;
        }
        return $this->makeWxPreOrder($status['orderPrice']);
    }

    /**
     * @param $totalPrice
     * @return array
     * @throws Exception
     * @throws TokenException
     * @throws \WxPayException
     */
    private function makeWxPreOrder($totalPrice) {
        $openid = Token::getCurrentTokenVar('openid');
        if (!$openid) {
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNo);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('haha');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));
        $wxConfig = new \WxPayConfig();
        return $this->getPaySignature($wxConfig,$wxOrderData);
    }

    /**
     * @param $wxConfig
     * @param $wxOrderData
     * @return array
     * @throws \WxPayException
     */
    private function getPaySignature($wxConfig,$wxOrderData) {

        $wxOrder = \WxPayApi::unifiedOrder($wxConfig,$wxOrderData);

        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            Log::record($wxOrder,'error');
            Log::record('获取预订单支付失败','error');
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxConfig,$wxOrder);
        return $signature;
    }

    /**
     * @param $wxConfig
     * @param $wxOrder
     * @return array
     * @throws \WxPayException
     */
    private function sign($wxConfig,$wxOrder) {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time().mt_rand(0,1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id = '. $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $rawValues = $jsApiPayData->GetValues();
        $sign = $jsApiPayData->MakeSign($wxConfig);
        $rawValues['PaySign'] = $sign;
        unset($rawValues['AppId']);
        return $rawValues;
    }

    /**
     * @param $wxOrder
     */
    private function recordPreOrder($wxOrder) {
        OrderModel::where('id','=',$this->orderID)
            ->update(['prepay_id' => $wxOrder['prepay_id']]);
    }

    /**
     * @return bool
     * @throws Exception
     * @throws OrderException
     * @throws TokenException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function checkOrderValidate() {
        $order = OrderModel::where('id','=',$this->orderID)->find();
        if (!$order) {
            throw new OrderException();
        }
        if (!Token::isValidOperate($order->user_id)) {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' =>10003
            ]);
        }
        if ($order->status != OrderStatusEnum::UNPAID) {
            throw new OrderException([
                'msg' => '该订单已经被支付过了',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }
         $this->orderNo = $order->order_no;
        return true;
    }
}