<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{

  protected $fillable = [
    'province',
    'city',
    'district',
    'address',
    'zip',
    'contact_name',
    'contact_phone',
    'last_used_at',
  ];

  /*
  表示 last_used_at 字段是一个时间日期类型，在之后的代码中 $address->last_used_at
  返回的就是一个时间日期对象（确切说是 Carbon 对象，Carbon 是 Laravel 默认使用的时间日期处理类）。
  */
  protected $dates = ['last_used_at'];

  // full_address 并非真实字段，在页面 JSON 序列化时，没有携带
  protected $appends = ['full_address'];    // 通过添加到appends，Laravel序列化该模型时，也会把 full_address 序列化进来

  

  // 收货地址-用户
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /*
  创建了一个访问器，在之后的代码里可以直接通过 $address->full_address
  来获取完整的地址，而不用每次都去拼接。
  */
  public function getFullAddressAttribute()
  {
    return "{$this->province}{$this->city}{$this->district}{$this->address}";
  }
}
