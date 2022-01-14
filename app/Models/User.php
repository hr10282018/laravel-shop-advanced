<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Encore\Admin\Traits\DefaultDatetimeFormat;// 后台-格式化日期显示


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
}
