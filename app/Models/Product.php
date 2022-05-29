<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{

  const TYPE_NORMAL = 'normal';
  const TYPE_CROWDFUNDING = 'crowdfunding';

  public static $typeMap=[
    self::TYPE_NORMAL => '普通商品',
    self::TYPE_CROWDFUNDING => '众筹商品',
  ];

  protected $fillable = [
    'title',
    'description',
    'image',
    'on_sale',
    'rating',
    'sold_count',
    'review_count',
    'price',
    'type'
  ];

  protected $casts = [
    'on_sale' => 'boolean', // on_sale 是一个布尔类型的字段
  ];

  // 商品图片访问器-用来返回绝对路径
  public function getImageUrlAttribute()  // 前端通过image_url即可使用该函数(模型访问器会自动把下划线改为驼峰,则image_url对应的就是getImageUrlAttribute)
  {
    // 如果 image 字段本身就已经是完整的 url 就直接返回
    if (Str::startsWith($this->attributes['image'], ['http://', 'https://'])) {
      return $this->attributes['image'];
    }
    // 这里 \Storage::disk('public') 的参数public需要和我们在 config/admin.php 里面的 upload.disk 配置一致。
    return \Storage::disk('public')->url($this->attributes['image']);
  }


  // 商品-SKU
  public function skus()
  {
    return $this->hasMany(ProductSku::class); // 一个商品有多个SKU
  }

  // 一个商品 属于一个 类目
  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  // 一个商品有一个众筹
  public function crowdfunding(){
    return $this->hasOne(CrowdfundingProduct::class);
  }

}
