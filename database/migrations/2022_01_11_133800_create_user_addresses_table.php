<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
          // 收货地址
          $table->bigIncrements('id');
          $table->unsignedBigInteger('user_id');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->string('province'); // 省
          $table->string('city');     // 市
          $table->string('district'); // 区
          $table->string('address');    // 详细地址
          $table->unsignedInteger('zip');   // 邮编
          $table->string('contact_name');   // 联系人姓名
          $table->string('contact_phone');
          $table->dateTime('last_used_at')->nullable(); // 最后一次使用时间
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
        Schema::dropIfExists('user_addresses');
    }
}
