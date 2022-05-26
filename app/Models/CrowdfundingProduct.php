<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\DefaultDatetimeFormat;

class CrowdfundingProduct extends Model
{
  use DefaultDatetimeFormat;

  // 定义众筹的 3 种状态
  const STATUS_FUNDING='funding';
  const STATUS_SUCCESS = 'success';
  const STATUS_FAIL = 'fail';

  public static $statusMap=[
    self::STATUS_FUNDING => '众筹中',
    self::STATUS_SUCCESS => '众筹成功',
    self::STATUS_FAIL    => '众筹失败',
  ];

  protected $fillable=['total_amount','target_amount','user_count','status','end_at'];
  
  protected $dates=['end_at'];  // end_at 自动转为Carbon类型
  
  public $timestamps=false;


  // 定义一个 percent 访问器，返回当前众筹进度
  public function getPercentAttribute(){
    // 已筹金额除以目标金额
    $value=$this->attributes['total_amount'] / $this->attributes['target_amount'];
    return floatval(number_format($value*100,2,'.',''));  // 第三个参数指定小数点字符串；第四个指定千位分隔符字符串

  } 


  // 一个众筹属于一个商品
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

}
