<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Encore\Admin\Traits\DefaultDatetimeFormat; // 后台-格式化日期显示


class User extends Authenticatable implements MustVerifyEmail
{
  use Notifiable;
  use DefaultDatetimeFormat;  // 后台-格式化日期显示

  protected $fillable = [
    'name', 'email', 'password',
  ];


  protected $hidden = [
    'password', 'remember_token',
  ];


  protected $casts = [
    'email_verified_at' => 'datetime',
  ];


  // 用户-收货地址
  public function addresses()
  {
    return $this->hasMany(UserAddress::class);  // 一个用户有多个收货地址
  }

  // 用户-收藏商品
  public function favoriteProducts()
  {
    /*
      belongsToMany()-用于定义一个多对多的关联，第一个参数是关联的模型类名，第二个参数是中间表的表名。
      withTimestamps()-表示中间表带有时间戳字段
      orderBy('user_favorite_products.created_at', 'desc')-代表默认的排序方式是根据中间表的创建时间倒序排序。
    */
    return $this->belongsToMany(Product::class, 'user_favorite_products')
      ->withTimestamps()
      ->orderBy('user_favorite_products.created_at', 'desc');
  }

  // 用户-商品购物车
  public function cartItems()
  {
    return $this->hasMany(CartItem::class); // 一个用户可以加入多个商品到购物车
  }
}

