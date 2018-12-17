<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/19
 * Time: 17:24
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\Order as OrderModel;
use app\api\model\UserAddress;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;

class Order
{
    //订单的商品列表，也就是客户端传递过来的products参数
    protected $oProducts;
    //真实的商品信息（包括库存量）
    protected $products;
    protected $uid;

    /**
     * @param $uid
     * @param $oProducts
     * @return array
     * @throws Exception
     * @throws OrderException
     * @throws \Exception
     * @throws \think\exception\DbException
     */
    public function place($uid,$oProducts) {

        $this->oProducts = $oProducts;
        $this->products = $this->getProductByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if (!$status['pass']) {
            $status['order_id'] = -1;
            return $status;
        }
        //开始创建订单
        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    private function snapOrder($status) {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' => ''
        ];
        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if (count($this->products) > 1) {
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    /**
     * @param $snap
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    private function createOrder($snap) {
        Db::startTrans();
        try {
            $orderNo = self::makeOrderNo();
            $order = new OrderModel();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_name = $snap['snapName'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();
            $orderID = $order->id;
            $create_time = $order->create_time;
            foreach ($this->oProducts as &$p) {
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        } catch (Exception $ex) {
            Db::rollback();
            throw $ex;
        }
    }

    public static function makeOrderNo() {
        $yCode = ['A','B','C','D','E','F','G','I','J'];
        $orderSn = $yCode[intval(date('Y') - 2018)] . strtoupper(dechex(date('m')) .
                date('d') . substr(time(),-5) . substr(microtime(),2,5)) .
                sprintf('%02d',rand(0,99));
        return $orderSn;
    }

    /**
     * @return array
     * @throws UserException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getUserAddress() {
        $userAddress = UserAddress::where('user_id','=',$this->uid)->find();
        if (!$userAddress) {
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败',
                'errorCode' => 60001
            ]);
        }
        return $userAddress->toArray();
    }

    /**
     * 检测库存量
     * @param $orderID
     * @return array
     * @throws OrderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkOrderStock($orderID) {
        $oProducts = OrderProduct::where('order_id','=',$orderID)->select();
        $this->oProducts = $oProducts;
        $this->products = $this->getProductByOrder($oProducts);
        $status = $this->getOrderStatus();
        return $status;

    }
    /**
     * 获取订单状态
     * @return array
     * @throws OrderException
     */
    private function getOrderStatus() {
        $status = [
            'pass' => true,
            'orderPrice' =>0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];
        foreach ($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus($oProduct['product_id'],$oProduct['count'],$this->products);

            $status['pass'] = $pStatus['haveStock'];
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['counts'];
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;
    }

    /**
     * 获取商品状态
     * @param $oPID
     * @param $oCount
     * @param $products
     * @return array
     * @throws OrderException
     */
    private function getProductStatus($oPID,$oCount,$products) {
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'counts' => 0,
            'name' => '',
            'price' => 0,
            'main_img_url' => null,
            'totalPrice' => 0
        ];
        $product = [];
        for ($i = 0;$i < count($products);$i++) {
            if ($oPID == $products[$i]['id']) {
                $pIndex = $i;
                $product = $products[$i];
            }
        }
        if ($pIndex == -1) {
            throw new OrderException([
                'msg' => 'id为'.$oPID.'的商品不存在，创建订单失败'
            ]);
        }else {
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['counts'] = $oCount;
            $pStatus['price'] = $product['price'];
            $pStatus['main_img_url'] = $product['main_img_url'];
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            $pStatus['haveStock'] = ($product['stock'] >= $oCount);
        }
        return $pStatus;
    }

    /**
     * 根据订单获取数据库商品信息
     * @param $oProducts
     * @return mixed
     * @throws \think\exception\DbException
     */
    private function getProductByOrder($oProducts) {
        $oPIDs = [];
        foreach ($oProducts as $item) {
            array_push($oPIDs,$item['product_id']);
        }

        $products = Product::all($oPIDs)
            ->visible(['id','price','stock','name','main_img_url'])
            ->toArray();
        return $products;
    }

    /**
     * 发送通知消息
     * @param $orderID
     * @param string $jumpPage
     * @return bool
     * @throws OrderException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function delivery($orderID,$jumpPage = ''){
        $order = OrderModel::where('id','=',$orderID)->find();
        if (!$order) {
            throw new OrderException();
        }
        if ($order->status != OrderStatusEnum::PAID) {
            throw new OrderException([
                'msg' => '订单还未支付',
                'errorCode' => 80002,
                'code' => 403
            ]);
        }
        $order->save();
        $message = new DeliveryMessage();
        return $message->sendDeliveryMessage($order,$jumpPage);
    }
}