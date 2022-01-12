<?php

use Illuminate\Support\Facades\Route;



Route::get('/', 'PagesController@root')->name('root');

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
});


