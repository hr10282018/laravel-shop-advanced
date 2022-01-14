<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSkusTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('product_skus', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('title');          // SKU名称
      $table->string('description');    // SKU描述
      $table->decimal('price', 10, 2);
      $table->unsignedInteger('stock');   // 库存
      $table->unsignedBigInteger('product_id'); // 所属商品id
      $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade'); // 外键
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
    Schema::dropIfExists('product_skus');
  }
}
