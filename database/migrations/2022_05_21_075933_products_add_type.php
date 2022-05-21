<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductsAddType extends Migration
{
  
  public function up()
  {
    Schema::table('products', function (Blueprint $table) {
      $table->string('type')->after('id')->default(\App\Models\Product::TYPE_NORMAL)->index();   // 类型，区分众筹还是普通商品
    });
  }

  
  public function down()
  {
    Schema::table('products', function (Blueprint $table) {
      $table->dropColumn('type');
    });
  }
}
