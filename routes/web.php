<?php

use Illuminate\Support\Facades\Route;



Route::get('/', 'PagesController@root')->name('root');

// 用户-登录注册
Auth::routes(['verify' => true]);

// 用户-收货地址
/*
  auth中间件代表需要登录，verified中间件代表需要经过邮箱验证
*/
Route::group(['middleware' => ['auth', 'verified']], function() {
  Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
});


