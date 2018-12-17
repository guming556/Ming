<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/16
 * Time: 19:12
 */

namespace app\api\service;

use app\api\model\User;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;

class DeliveryMessage extends WxMessage
{
    //微信发送模板消息的模板ID
    const DELIVERY_MSG_ID = 'efUeLkehnxzj9Qp2tUEYoyVkvf1WiNWXoy8e1FlDDLU';

    public function sendDeliveryMessage($order,$tplJumpPage = '') {
        if (!$order) {
            throw new OrderException($this->getUserOpenID($order->user_id));
        }
        $this->tplID = self::DELIVERY_MSG_ID;
        $this->formID = $order->prepay_id;
        $this->page = $tplJumpPage;
        $this->prepareMessageData($order);
        $this->emphasisKeyWord = 'keyword2.DATA';
        return parent::sendMessage();

    }

    private function prepareMessageData($order){
        $dt = new \DateTime();
        $data = [
            'keyword1' => [
                'value' => '千里马速运'
            ],
            'keyword2' => [
                'value' => $order->order_no
            ],
            'keyword3' => [
                'value' => $order->snap_name,
                'color' => '#27408B'
            ],
            'keyword4' => [
                'value' => $dt->format('Y-m-d H:i:s')
            ]
        ];
        $this->data = $data;
    }

    private function getUserOpenID($uid) {
        $user = User::get($uid);
        if (!$user) {
            throw new UserException();
        }
        return $user->openid;
    }
}