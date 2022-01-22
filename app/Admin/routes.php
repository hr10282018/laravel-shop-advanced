<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
  'prefix'        => config('admin.route.prefix'),
  'namespace'     => config('admin.route.namespace'),
  'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

  $router->get('/', 'HomeController@index')->name('admin.home');  // 主页

  $router->get('users', 'UsersController@index'); // 用户列表

  $router->get('products', 'ProductsController@index'); // 商品列表

  $router->get('products/create', 'ProductsController@create'); // 创建商品
  // store方法在Encore\Admin\Controllers\AdminController中的use HasResourceActions定义了
  $router->post('products', 'ProductsController@store');        // 处理创建商品数据

  $router->get('products/{id}/edit', 'ProductsController@edit');  // 编辑商品
  $router->put('products/{id}', 'ProductsController@update');     //

  $router->get('orders', 'OrdersController@index')->name('admin.orders.index'); // 订单列表
  $router->get('orders/{order}', 'OrdersController@show')->name('admin.orders.show'); // 订单详情
  $router->post('orders/{order}/ship', 'OrdersController@ship')->name('admin.orders.ship');// 订单发货

  
});
