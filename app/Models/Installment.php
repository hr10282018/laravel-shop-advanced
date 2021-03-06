<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
  const STATUS_PENDING = 'pending';
  const STATUS_REPAYING = 'repaying';
  const STATUS_FINISHED = 'finished';

  public static $statusMap = [
    self::STATUS_PENDING  => '未执行',
    self::STATUS_REPAYING => '还款中',
    self::STATUS_FINISHED => '已完成'
  ];

  protected $fillable = ['no', 'total_amount', 'count', 'fee_rate', 'fine_rate', 'status'];

  protected static function boot()
  {
    parent::boot();
    // 监听模型创建事件，在写入数据库之前触发
    static::creating(function ($model) {
      // 如果模型的 no 字段为空
      if (!$model->no) {
        // 调用 findAvailableNo 生成分期流水号
        $model->no = static::findAvailableNo();
        // 如果生成失败。则终止创建订单
        if (!$model->no) {
          return false;
        }
      }
    });
  }

  public static function findAvailableNo()
  {
    // 分期流水号前缀
    $prefix = date('YmdHis');
    for ($i = 0; $i < 10; $i++) {
      // 随机生成 6 位数字
      $no = $prefix . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT); // 拼接字符，不到6位左边填充0
      // 判断是否已经存在
      if (!static::query()->where('no', $no)->exists()) {
        return $no;
      }
    }
    \log::warning('find installment no failed');  // 写入日志
    return false;
  }

  // 判断当前分期付款的所有还款计划，是否全部退款成功，成功则修改状态
  public function refreshRefundStatus()
  {
    $allSuccess = true;
    // 重新加载 items，保证与数据库中数据同步
    $this->load(['items']);
    foreach ($this->items as $item) {
      if ($item->paid_at && $item->refund_status !== InstallmentItem::REFUND_STATUS_SUCCESS) {
        $allSuccess = false;
        break;
      }
    }
    if ($allSuccess) {
      $this->order->update([
        'refund_status' => Order::REFUND_STATUS_SUCCESS,
      ]);
    }
  }


  // 一个分期信息属于一个用户
  public function user()
  {
    return $this->belongsTo(User::class);
  }
  // 一个分期信息 属于一个订单
  public function order()
  {
    return $this->belongsTo(Order::class);
  }
  // 一个分期信息 有多个还款计划
  public function items()
  {
    return $this->hasMany(InstallmentItem::class);
  }
}
