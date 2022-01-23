<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrdersAddCouponCodeId extends Migration
{

  public function up()
  {
    Schema::table('orders', function (Blueprint $table) {
      $table->unsignedBigInteger('coupon_code_id')->nullable()->after('paid_at');
      // onDelete-删除有关联订单的优惠券时，自动把coupon_code_id字段设为null，不能删了优惠券就把订单删了
      $table->foreign('coupon_code_id')->references('id')->on('coupon_codes')->onDelete('set null');
    });
  }


  public function down()
  {
    Schema::table('orders', function (Blueprint $table) {
      //该方法参数可以是数组或者字符,如果是字符串则代表删除外键名为该字符串的外键，而如果是数组的话则会删除该数组中字段所对应的外键
      $table->dropForeign(['coupon_code_id']);  // 删除关联调用，
      $table->dropColumn('coupon_code_id');   //
    });
  }
}
