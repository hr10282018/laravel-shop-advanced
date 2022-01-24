<?php

use Illuminate\Support\Facades\Route;



// Route::get('/', 'PagesController@root')->name('root');

// 用户-登录注册
Auth::routes(['verify' => true]);


// auth中间件代表需要登录，verified中间件代表需要经过邮箱验证
Route::group(['middleware' => ['auth', 'verified']], function() {
  // 用户-收货地址
  Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');

  //用户-新建收货地址
  Route::get('user_addresses/create','UserAddressesController@create')->name('user_addresses.create');

  // 用户-处理收货表单
  Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');

  // 用户-修改收货地址信息
  Route::get('user_addresses/{user_address}', 'UserAddressesController@edit')->name('user_addresses.edit');

  // 用户-处理修改收货地址
  Route::put('user_addresses/{user_address}', 'UserAddressesController@update')->name('user_addresses.update');

  // 用户-删除收货地址
  Route::delete('user_addresses/{user_address}', 'UserAddressesController@destroy')->name('user_addresses.destroy');

  // 用户-收藏或取消收藏商品
  Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');
  Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');

  // 用户-收藏商品列表
  Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');

  // 用户-sku商品加入购物车
  Route::post('cart', 'CartController@add')->name('cart.add');

  // 用户-查看购物车
  Route::get('cart', 'CartController@index')->name('cart.index');

  //用户-从购物车移除sku商品
  Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');

  // 用户-购物车下单
  Route::post('orders', 'OrdersController@store')->name('orders.store');

  // 用户-订单列表
  Route::get('orders', 'OrdersController@index')->name('orders.index');

  // 用户-订单详情
  Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');

  // 用户-支付订单(支付宝)
  Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')->name('payment.alipay');
  // 支付宝前端回调
  Route::get('payment/alipay/return', 'PaymentController@alipayReturn')->name('payment.alipay.return');

  // 用户-确认收货
  Route::post('orders/{order}/received', 'OrdersController@received')->name('orders.received');

  // 用户-评价页面
  Route::get('orders/{order}/review', 'OrdersController@review')->name('orders.review.show');
  // 用户-处理评价
  Route::post('orders/{order}/review', 'OrdersController@sendReview')->name('orders.review.store');

  // 用户-申请退款
  Route::post('orders/{order}/apply_refund', 'OrdersController@applyRefund')->name('orders.apply_refund');

  // 用户-检查优惠券
  Route::get('coupon_codes/{code}', 'CouponCodesController@show')->name('coupon_codes.show');

  
});

// 商品列表
Route::redirect('/', '/products')->name('root');  // 首页跳转-商品列表
Route::get('products', 'ProductsController@index')->name('products.index');

// 商品详情
Route::get('products/{product}', 'ProductsController@show')->name('products.show');

// 支付宝后端回调-不能在auth中间件里，因为支付宝的服务器请求不会带有认证信息
Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');


// 测试支付宝支付
// Route::get('alipay', function() {
//   return app('alipay')->web([     // web()-表示用电脑支付
//       'out_trade_no' => time(),
//       'total_amount' => '0.1',
//       'subject' => 'test subject - 测试',
//   ]);
// });




