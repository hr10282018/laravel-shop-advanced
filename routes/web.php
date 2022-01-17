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

  //用户-收藏商品列表
  Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');


});

// 商品列表
Route::redirect('/', '/products')->name('root');  // 首页跳转-商品列表
Route::get('products', 'ProductsController@index')->name('products.index');

// 商品详情
Route::get('products/{product}', 'ProductsController@show')->name('products.show');





