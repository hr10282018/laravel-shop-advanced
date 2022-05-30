<?php

namespace App\Console\Commands\Cron;

use App\Models\Installment;
use App\Models\InstallmentItem;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateInstallmentFine extends Command
{
  
  protected $signature = 'cron:calculate-installment-fine';

 
  protected $description = '计算分期付款逾期费';

  
  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    InstallmentItem::query()
    // 预加载分期付款数据，避免N+1
    ->with(['installment'])
    ->whereHas('installment',function($query){
      $query->where('status',Installment::STATUS_REPAYING);
    })
    ->where('due_date','<=',Carbon::now())  // 还款截止日期在当前时间之前
    ->whereNull('paid_at')// 尚未付款
    ->chunkById(1000,function($items){    // 使用 chunkById，避免一次性查询太多记录
      // 遍历查询出来的还款计划
      foreach($items as $item){
        // 通过 Carboon 对象的 diffInDays 直接得到逾期天数
        $overdueDays = Carbon::now()->diffInDays($item->due_date);
        // 本金用于手续费之和
        $base=big_number($item->base)->add($item->fee)->getValue();
        // 计算逾期费
        $fine=big_number($base)
        ->multiply($overdueDays)
        ->multiply($item->installment->fine_rate)
        ->divide(100)
        ->getValue();

        // 避免逾期费高于本金与手续费之和，使用 compareTo 方法判断
        // 如果 $fine 大于 $base，则 compareTo 会返回 1，相等返回 0，小于返回 -1
        $fine = big_number($fine)->compareTo($base) === 1 ? $base : $fine;
        $item->update([
          'fine'  => $fine,
        ]);
      }
    });

  }
}
