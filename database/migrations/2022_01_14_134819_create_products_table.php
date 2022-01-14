<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('products', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('title');
      $table->text('description');  // 商品详情
      $table->string('image');
      $table->boolean('on_sale')->default(true);  // 商品是否正在售卖
      $table->float('rating')->default(5);    // 商品平均评分
      $table->unsignedInteger('sold_count')->default(0);  // 销量
      $table->unsignedInteger('review_count')->default(0);    // 评价数量
      $table->decimal('price', 10, 2);
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
    Schema::dropIfExists('products');
  }
}
