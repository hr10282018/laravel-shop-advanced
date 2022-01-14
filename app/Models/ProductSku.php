<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
  protected $fillable = ['title', 'description', 'price', 'stock'];

  // SKU-所属商品
  public function product()
  {
    return $this->belongsTo(Product::class);  // 一个SKU属于一个商品
  }
}



