<?php

namespace App\Console\Commands\Elasticsearch;

use App\Models\Product;
use Illuminate\Console\Command;

class SyncProducts extends Command
{

  protected $signature = 'es:sync-products {--index=products}'; // 输入索引名


  protected $description = '商品数据同步到 Elasticsearch';

  public function __construct()
  {
    parent::__construct();
  }


  public function handle()
  {

    $es = app('es');

    Product::query()
      // 预加载 sku 和 商品属性数据，避免 n+1
      ->with(['skus', 'properties'])
      // 使用 chunkById 避免一次性加载过多数据
      ->chunkById(100, function ($products) use ($es) {
        $this->info(sprintf('正在同步 ID 范围 %s 至 %s 的商品', $products->first()->id, $products->last()->id));

        // 初始化请求体
        $req = ['body' => []];
        // 遍历商品
        foreach ($products  as $product) {
          // 将商品模型转为 Elasticsearch 所用的数组
          $data = $product->toESArray();
          $req['body'][] = [
            'index' => [
              '_index' => $this->option('index'),     // 从参数中读取索引名
              '_id'    => $data['id'],
            //'_type'  => '_doc', // 加上这行
            ],
          ];
          $req['body'][]=$data;
        }

        try{
          // 使用 bulk 方法批量创建
          $es->bulk($req);      // 一次API请求完成一批操作
          
        }catch(\Exception $e){
          $this->error($e->getMessage());
        }

      });
      $this->info('同步完成');
  }
}
