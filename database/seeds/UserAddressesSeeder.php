<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserAddress;

class UserAddressesSeeder extends Seeder
{

  public function run()
  {
    User::all()->each(function (User $user) {   // each()-遍历集合中每个元素
      /*

        factory(UserAddress::class, random_int(1, 3)) 对每一个用户，产生一个 1 - 3 的随机数作为我们要为个用户生成地址的个数
        create(['user_id' => $user->id]) 将随机生成的数据写入数据库，同时指定这批数据的 user_id 字段统一为当前循环的用户 ID。
      */
      factory(UserAddress::class, random_int(1, 3))->create(['user_id' => $user->id]);
    });
  }

}
