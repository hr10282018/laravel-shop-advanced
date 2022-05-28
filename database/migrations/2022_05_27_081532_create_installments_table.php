<?php

use Encore\Admin\Form\Tab;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('installments', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('no')->unique();
      $table->unsignedBigInteger('user_id');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->unsignedBigInteger('order_id');
      $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
      $table->decimal('total_amount');  // 总本金
      $table->unsignedInteger('count'); // 还款期数
      $table->float('fee_rate');  // 手续费率
      $table->float('fine_rate'); // 逾期费率
      $table->string('status')->default(\App\Models\Installment::STATUS_PENDING);   // 还款状态
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('installments');
  }
}
