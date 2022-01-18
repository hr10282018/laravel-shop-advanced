<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
  protected $fillable = ['amount', 'price', 'rating', 'review', 'reviewed_at'];
  protected $dates = ['reviewed_at'];
  public $timestamps = false; // 表示这个模型没有 created_at 和 updated_at 两个时间戳字段。

  // SKU商品订单-商品
  public function product()
  {
    return $this->belongsTo(Product::class);  // 一个SKU商品订单属于一种商品
  }

  // SKU商品订单-SKU商品
  public function productSku()
  {
    return $this->belongsTo(ProductSku::class); // 一个SKU商品订单属于一个SKU商品
  }

  // SKU商品订单-订单
  public function order()
  {
    return $this->belongsTo(Order::class);    // 一个SKU商品订单属于一个订单
  }
}
