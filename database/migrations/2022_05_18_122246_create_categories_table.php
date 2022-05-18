<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('categories', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('name');
      $table->unsignedBigInteger('parent_id')->nullable();    // 父类目 id
      $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
      $table->boolean('is_directory');
      $table->unsignedInteger('level');     // 层级
      $table->string('path');     // 记录该类目的所有父类目 id
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
    Schema::dropIfExists('categories');
  }
}
