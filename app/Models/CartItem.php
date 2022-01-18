<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
  protected $fillable = ['amount'];
  public $timestamps = false;

  // 商品-用户
  public function user()
  {
    return $this->belongsTo(User::class); // 一个购物车商品属于一个用户
  }

  // 商品-商品SKU
  public function productSku()
  {
    return $this->belongsTo(ProductSku::class); // 一个购物车商品属于一个商品SKU
  }
}
