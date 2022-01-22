<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\OrderReviewed;
use App\Listeners\UpdateProductRating;

class EventServiceProvider extends ServiceProvider
{

  protected $listen = [
    Registered::class => [
      SendEmailVerificationNotification::class,
    ],

    OrderReviewed::class => [   // 监听用户修改商品评分
      UpdateProductRating::class,
    ],
  ];


  public function boot()
  {
    parent::boot();

    //
  }
}
