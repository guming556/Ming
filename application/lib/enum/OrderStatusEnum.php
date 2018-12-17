<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/22
 * Time: 1:24
 */

namespace app\lib\enum;


class OrderStatusEnum
{
    //待支付
    const UNPAID = 1;
    //已支付
    const PAID = 2;
    //已发货
    const DELIVERED = 3;
    //已支付，但库存不足
    const PAID_BUT_OUT_OF = 4;
}