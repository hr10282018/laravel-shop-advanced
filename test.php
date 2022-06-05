<?php

namespace App\Console\Commands\Elasticsearch;
class A{

}
$indices = [test\B::class];

echo $indices[0];

foreach($indices as $value){
  echo ($value::echo());
}
//var_dump($indices);

namespace App\Console\Commands\Elasticsearch\test;
class B{
  public static function echo()
  {
    return 'B类';
  }
}
