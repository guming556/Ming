<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::group('api/:version',function () {
    //获取轮播图banner数据
    Route::get('/banner/:id','api/:version.Banner/getBanner');

    //获取主页精选主题数据
    Route::get('/theme','api/:version.Theme/getSimpleList');

    //根据主题id值获取该主题下的商品数据
    Route::get('/theme/:id','api/:version.Theme/getComplexOne',[],['id'=>'\d+']);

    Route::group('/product',function () {

        //获取最近新品数据
        Route::get('/recent','api/:version.Product/getRecent');

        //根据分类获取商品
        Route::get('/by_category','api/:version.Product/getAllByCategory');

        //根据id获取商品详情
        Route::get('/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
    });

    //获取所有分类
    Route::get('/category/all','api/:version.Category/getAllCategories');

    //获取token值
    Route::post('/token/user','api/:version.Token/getToken');

    //校验token
    Route::post('/token/verify','api/:version.Token/verifyToken');

    Route::post('/token/app','api/:version.Token/getAppToken');

    //新增或更新收货地址
    Route::post('/address','api/:version.Address/createOrUpdateAddress');

    Route::get('/address','api/:version.Address/getUserAddress');

    //创建订单
    Route::post('/order','api/:version.Order/placeOrder');

    //获取历史订单列表
    Route::get('/order/by_user','api/:version.Order/getSummaryByUser');

    Route::get('/order/paginate','api/:version.Order/getSummary');

    Route::get('/order/:id','api/:version.Order/getDetail',[],['id'=>'\d+']);

    Route::put('/order/delivery','api/:version.Order/delivery');

    //
    Route::post('/pay/pre_order','api/:version.Pay/getPreOrder');

    //提供给微信访问的回调接口
    Route::post('/pay/notify','api/:version.Pay/receiveNotify');

});