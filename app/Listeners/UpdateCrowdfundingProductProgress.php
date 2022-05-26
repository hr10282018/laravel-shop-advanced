<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateCrowdfundingProductProgress implements ShouldQueue
{
 
  // public function __construct()
  // {
  //   //
  // }

  
  public function handle(OrderPaid $event)
  {
    $order=$event->getOrder();
    // 如果订单类型不是众筹商品订单，无需处理
    if($order->type !== Order::TYPE_CROWDFUNDING){
      return;
    }
    $crowdfunding=$order->items[0]->product->crowdfunding;

    $data=Order::query()
    ->where('type',Order::TYPE_CROWDFUNDING)
    ->whereNotNull('paid_at')   // 已支付
    ->whereHas('items',function($query) use ($crowdfunding){
      
      // 并且 包含了本商品
      $query->where('product_id',$crowdfunding->product_id);
    })
    ->first([   // first()-接受一个数组参数，表示要查询的字段
      // 取出订单总金额
      \DB::raw('sum(total_amount) as total_amount'),

      // 取出去重的支持用户数
      \DB::raw('count(distinct(user_id)) as user_count'),
    ]);

    echo $data->total_amount;
    
    $crowdfunding->update([
      'total_amount' => $data->total_amount,
      'user_count'   => $data->user_count,
    ]);


  }
}
