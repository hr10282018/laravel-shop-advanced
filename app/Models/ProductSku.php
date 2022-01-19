<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InternalException;

class ProductSku extends Model
{
  protected $fillable = ['title', 'description', 'price', 'stock'];

  // 减库存
  public function decreaseStock($amount)
  {
    if ($amount < 0) {
      throw new InternalException('减库存不可小于0');
    }
    // decrement()-该方法用来减少字段的值，会返回影响的行数
    // 类似于 update product_skus set stock = stock - $amount where id = $id and stock >= $amount
    return $this->where('id', $this->id)->where('stock', '>=', $amount)->decrement('stock', $amount);
  }

  // 加库存
  public function addStock($amount)
  {
    if ($amount < 0) {
      throw new InternalException('加库存不可小于0');
    }
    $this->increment('stock', $amount);
  }

  // SKU-所属商品
  public function product()
  {
    return $this->belongsTo(Product::class);  // 一个SKU属于一个商品
  }
}
