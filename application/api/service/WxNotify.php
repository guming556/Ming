<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/10/18
 * Time: 2:03
 */

namespace app\api\service;

use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\model\Product as ProductModel;
use app\api\service\Order as OrderService;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');
class WxNotify extends \WxPayNotify
{
    /**
     * @param \WxPayNotifyResults $objData
     * @param \WxPayConfigInterface $config
     * @param string $msg
     * @return bool|\true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    public function NotifyProcess($objData, $config, &$msg)
    {
        if ($objData['result_code'] == 'SUCCESS'){
            $orderNo = $objData['out_trade_no'];
            Db::startTrans();
            try{
                $order = OrderModel::where('order_no', '=', $orderNo)->find();
                if ($order->status == 1) {
                    $service = new OrderService();
                    $stockStatus = $service->checkOrderStock($order->id);
                    if ($stockStatus['pass']) {
                        $this->updateOrderStatus($order->id,true);
                        $this->reduceStock($stockStatus);
                    } else {
                        $this->updateOrderStatus($order->id,false);
                    }
                }
                Db::commit();
                return true;
            }catch (Exception $ex) {
                Db::rollback();
                Log::error($ex);
                return false;
            }
        }else {
            return true;
        }
    }

    /**
     * @param $orderID
     * @param $success
     */
    private function updateOrderStatus($orderID,$success){
        $status = $success?OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id','=',$orderID)->update(['status' => $status]);
    }

    /**
     * @param $stockStatus
     * @throws Exception
     */
    private function reduceStock($stockStatus){
        foreach ($stockStatus['pStatusArray'] as $singlePStatus) {
            ProductModel::where('id','=',$singlePStatus['id'])
                ->setDec('stock',$singlePStatus['count']);
        }
    }

}