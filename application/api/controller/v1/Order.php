<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/19
 * Time: 13:46
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;

class Order extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only'=>'placeorder'],
        'checkPrimaryScope' => ['only'=>'getdetail,getsummarybyuser,getsummary']
    ];

    /**
     * @return \think\response\Json
     * @throws \Exception
     * @throws \app\lib\exception\OrderException
     * @throws \app\lib\exception\ParamterException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function placeOrder() {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid,$products);
        return json($status);
    }

    /**
     * @param int $page
     * @param int $size
     * @return \think\response\Json
     * @throws \app\lib\exception\ParamterException
     * @throws \think\exception\DbException
     */
    public function getSummaryByUser($page = 1,$size = 10){
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid,$page,$size);
        if ($pagingOrders->isEmpty()) {
            return json([
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage()
            ]);
        }
        $data = $pagingOrders->hidden(['snap_items','snap_address','prepay_id'])->toArray();
        return json([
            'data' => $data,
            'current_page' => $pagingOrders->getCurrentPage()
        ]);
    }

    /**
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParamterException
     * @throws \think\exception\DbException
     */
    public function getSummary($page=1,$size=10) {
        (new PagingParameter())->goCheck();
        $pagingOrders = OrderModel::getSummaryByPage($page,$size);
        if ($pagingOrders->isEmpty()) {
            return json([
                'current_page' => $pagingOrders->currentPage(),
                'data' => []
            ]);
        }
        $data = $pagingOrders->hidden(['snap_item','snap_address'])->toArray();
        return json([
            'current_page' => $pagingOrders->currentPage(),
            'data' => $data
        ]);
    }

    /**
     * @param $id
     * @return \think\response\Json
     * @throws OrderException
     * @throws \app\lib\exception\ParamterException
     * @throws \think\exception\DbException
     */
    public function getDetail($id) {
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail) {
            throw new OrderException();
        }
        return json($orderDetail->hidden(['prepay_id']));
    }

    public function delivery($id) {
        (new IDMustBePositiveInt())->goCheck();
        $order = new OrderService();
        $success = $order->delivery($id);
        if ($success) {
            return new SuccessMessage();
        }
    }

}