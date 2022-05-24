<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\OrderReviewed;
use App\Listeners\UpdateProductRating;
use App\Events\OrderPaid;
use App\Listeners\UpdateProductSoldCount;
use App\Listeners\SendOrderPaidMail;
use App\Listeners\UpdateCrowdfundingProductProgress;

class EventServiceProvider extends ServiceProvider
{

  protected $listen = [
    Registered::class => [
      SendEmailVerificationNotification::class,
    ],

    OrderReviewed::class => [   // 订单评价 事件
      UpdateProductRating::class,  // 用户修改商品评分
    ],

    OrderPaid::class => [   // 订单支付后 事件
      UpdateProductSoldCount::class,    // 修改商品销量-监听器
      SendOrderPaidMail::class,       // 发送邮件
      UpdateCrowdfundingProductProgress::class,   // 修改众筹进度
    ],
  ];


  public function boot()
  {
    parent::boot();

    //
  }
}
