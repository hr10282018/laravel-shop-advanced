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

  // 处理用户退款
  $router->post('orders/{order}/refund', 'OrdersController@handleRefund')->name('admin.orders.handle_refund');

  // 优惠券列表
  $router->get('coupon_codes', 'CouponCodesController@index');

  // 优惠券-添加
  $router->post('coupon_codes', 'CouponCodesController@store');
  $router->get('coupon_codes/create', 'CouponCodesController@create');

  // 优惠券-修改
  $router->get('coupon_codes/{id}/edit', 'CouponCodesController@edit');
  $router->put('coupon_codes/{id}', 'CouponCodesController@update');

  // 优惠券-删除
  $router->delete('coupon_codes/{id}', 'CouponCodesController@destroy');
  
  // 类目
  $router->get('categories', 'CategoriesController@index');
  $router->get('categories/create', 'CategoriesController@create');
  $router->get('categories/{id}/edit', 'CategoriesController@edit');
  $router->post('categories', 'CategoriesController@store');
  $router->put('categories/{id}', 'CategoriesController@update');
  $router->delete('categories/{id}', 'CategoriesController@destroy');
  $router->get('api/categories', 'CategoriesController@apiIndex');    // ajax 



});
