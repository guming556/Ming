<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/8/26
 * Time: 23:49
 */

namespace app\api\controller;


use app\api\service\Token as TokenService;
use think\Controller;

class BaseController extends Controller
{
    protected function checkPrimaryScope() {
        TokenService::needPrimaryScope();
    }

    protected function checkExclusiveScope() {
        TokenService::needExclusiveScope();
    }
}