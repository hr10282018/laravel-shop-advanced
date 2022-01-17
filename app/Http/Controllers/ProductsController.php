<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Exceptions\InvalidRequestException;   // 自定义异常

class ProductsController extends Controller
{

  // 商品显示-查询排序
  public function index(Request $request)
  {
    // 创建一个查询构造器
    $builder = Product::query()->where('on_sale', true);
    // 判断是否有提交 search 参数，如果有就赋值给 $search 变量
    // search 参数用来模糊搜索商品
    if ($search = $request->input('search', '')) {
      $like = '%' . $search . '%';
      // 模糊搜索商品标题、商品详情、SKU 标题、SKU描述
      // SQL类似-select * from products where on_sale = 1 and ( title like xxx or description like xxx )
      $builder->where(function ($query) use ($like) {
        $query->where('title', 'like', $like)
          ->orWhere('description', 'like', $like)
          ->orWhereHas('skus', function ($query) use ($like) {
            $query->where('title', 'like', $like)
              ->orWhere('description', 'like', $like);
          });
      });
    }

    // 是否有提交 order 参数，如果有就赋值给 $order 变量
    // order 参数用来控制商品的排序规则
    if ($order = $request->input('order', '')) {
      // 是否是以 _asc 或者 _desc 结尾
      if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
        // 如果字符串的开头是这 3 个字符串之一，说明是一个合法的排序值
        if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
          // 根据传入的排序值来构造排序参数
          $builder->orderBy($m[1], $m[2]);
        }
      }
    }

    $products = $builder->paginate(16);


    return view('products.index', [
      'products' => $products,
      // 传回搜索参数到模板中
      'filters'  => [
        'search' => $search,
        'order'  => $order,
      ],
    ]);
  }

  // 商品详情
  public function show(Product $product, Request $request)
  {
    // 判断商品是否已经上架，如果没有上架则抛出异常。
    if (!$product->on_sale) {
      throw new InvalidRequestException('商品未上架');
    }

    $favored = false;
    // 用户未登录时返回的是 null，已登录时返回的是对应的用户对象
    if ($user = $request->user()) {
      // 从当前用户已收藏的商品中搜索 id 为当前商品 id 的商品
      // boolval() 函数用于把值转为布尔值

      $favored = boolval($user->favoriteProducts()->find($product->id));
      
    }

    return view('products.show', ['product' => $product, 'favored' => $favored]);
  }

  // 用户收藏商品
  public function favor(Product $product, Request $request)
  {
    $user = $request->user();
    // 判断用户是否已经收藏此商品,已经收藏不做任何操作
    if ($user->favoriteProducts()->find($product->id)) {
      return [];
    }
    // 未收藏通过attach将当前用户和此商品关联
    // attach() 方法的参数可以是模型的 id，也可以是模型对象本身，因此这里还可以写成 attach($product->id)
    $user->favoriteProducts()->attach($product);

    return [];
  }

  // 用户取消收藏商品
  public function disfavor(Product $product, Request $request)
  {
    $user = $request->user();
    $user->favoriteProducts()->detach($product);  //detach-用于取消多对多关联

    return [];
  }
}
