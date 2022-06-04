<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Exceptions\InvalidRequestException;   // 自定义异常
use App\Models\Category;
use App\Models\OrderItem;
use App\SearchBuilders\ProductSearchBuilder;
use App\Services\CategoryService;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsController extends Controller
{

  // 商品显示-查询排序
  // public function index(Request $request) 
  // {

  //   // 创建一个查询构造器
  //   $builder = Product::query()->where('on_sale', true);

  //   // 判断是否有提交 search 参数，如果有就赋值给 $search 变量
  //   // search 参数用来模糊搜索商品
  //   if ($search = $request->input('search', '')) {
  //     $like = '%' . $search . '%';
  //     // 模糊搜索商品标题、商品详情、SKU 标题、SKU描述
  //     // SQL类似-select * from products where on_sale = 1 and ( title like xxx or description like xxx )
  //     $builder->where(function ($query) use ($like) {
  //       $query->where('title', 'like', $like)
  //         ->orWhere('description', 'like', $like)
  //         ->orWhereHas('skus', function ($query) use ($like) {
  //           $query->where('title', 'like', $like)
  //             ->orWhere('description', 'like', $like);
  //         });
  //     });
  //   }

  //   // 如果有传入 category_id 字段，且在数据库有对应类目
  //   if($request->input('category_id') && $category=Category::find($request->input('category_id'))){
  //     if($category->is_directory){      // 如果这是一个父类目
  //       //  筛选出该父类目下所有子类目的商品
  //       $builder->whereHas('category',function($query) use ($category){ // whereHas -子查询
  //         // 例子：父类目的path为 -  子类目的path有 -1-、-1-2-  （所以like path+父类目id+'-%'）
  //         $query->where('path','like',$category->path.$category->id.'-%');
  //       }); 
  //     }else{
  //       // 如果不是一个父类目，则直接筛选此类目下的商品
  //       $builder->where('category_id',$category->id);
  //     }
  //   }


  //   // 是否有提交 order 参数，如果有就赋值给 $order 变量
  //   // order 参数用来控制商品的排序规则
  //   if ($order = $request->input('order', '')) {
  //     // 是否是以 _asc 或者 _desc 结尾
  //     if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
  //       // 如果字符串的开头是这 3 个字符串之一，说明是一个合法的排序值
  //       if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
  //         // 根据传入的排序值来构造排序参数
  //         $builder->orderBy($m[1], $m[2]);
  //       }
  //     }
  //   }

  //   $products = $builder->paginate(16);


  //   return view('products.index', [
  //     'products' => $products,
  //     // 传回搜索参数到模板中
  //     'filters'  => [
  //       'search' => $search,
  //       'order'  => $order,
  //     ],
  //     // 等价于 isset($category) ? $category : null
  //     'category' => $category ?? null,
  //     // 将类目树传给模板
  //     //'categoryTree' => $categoryService->getCategoryTree(),
  //   ]);
  // }

  // ES 查询
  public function index(Request $request)
  {
    $page = $request->input('page', 1);
    $perPage = 16;
    //dd($request->all());

    // 新建查询构造器对象，设置只搜索上架商品，设置分页
    $builder = (new ProductSearchBuilder())->onSale()->paginate($perPage, $page);


    // 是否有提交 order 参数，若有就赋值给 $order
    // order 参数用来控制商品的排序规则
    if ($order = $request->input('order', '')) {
      //dd($order);
      // 是否以 _asc 或 _desc 结尾
      if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {    // $m 返回数组匹配结果
        //dd($m);
        // 如果字符串
        if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
          // 调用查询构造器的排序
          $builder->orderBy($m[1], $m[2]);
        }
      }
    }

    // 类目
    if ($request->input('category_id') && $category = Category::find($request->input('category_id'))) {
      // 调用查询构造器的类目筛选
      $builder->category($category);
    }

    // 关键词
    if ($search = $request->input('search', '')) {

      //dd($search);
      // 将搜索词 根据空格分成数组，并过滤掉空项
      $keywords = array_filter(explode(' ', $search));

      $params['body']['query']['bool']['must'] = [];
      // 调用查询构造器的关键词筛选
      $builder->keywords($keywords);
    }

    // 只有当用户有输入搜索词或者使用了类目筛选的时候才会做聚合
    if ($search || isset($category)) {
      // 调用查询构造器的分面搜索
      $builder->aggregateProperties();
    }

    $propertyFilters = [];
    // 属性值搜索，从用户请求参数获取 filters
    if ($filterString = $request->input('filters')) {
      // 将获取到的字符串通过符号 | 转为数组
      $filterArray = explode('|', $filterString);
      foreach ($filterArray as $filter) {
        // 将字符串用符号 : 拆分两部分且分别赋值 $name 和 $value 两个变量
        list($name, $value) = explode(':', $filter);
        // 将用户筛选的属性添加到数组中
        $propertyFilters[$name] = $value;

        // 调用查询构造器的属性筛选
        $builder->propertyFilter($name, $value);
      }
    }

    //dd($params);

    $result = app('es')->search($builder->getParams());

    // 通过 collect 函数将返回结果转集合，并通过集合的 pluck 方法取返回商品的 id 数组
    $productIds = collect($result['hits']['hits'])->pluck('_id')->all();
    // 通过 whereIn 方法从数据库中读取商品数据
    $products = Product::query()
      ->whereIn('id', $productIds)
      ->orderByRaw(sprintf("FIND_IN_SET(id, '%s')", join(',', $productIds)))    // 要按照该顺序取出数据
      ->get();

    // 返回一个 LengthAwarePaginator 对象。 $result['hits']['total']['value'] 是总数
    $pager = new LengthAwarePaginator($products, $result['hits']['total']['value'], $perPage, $page, [
      'path' => route('products.index', false), // 手动构建分页的 url
    ]);

    $properties = [];
    // 如果返回结果里有 aggregations 字段，说明做了分面搜索
    if (isset($result['aggregations'])) {
      // 使用 collect 函数将返回值转集合
      $properties = collect($result['aggregations']['properties']['properties']['buckets'])
        ->map(function ($bucket) {
          // map 取出需要的字段
          return [
            'key' => $bucket['key'],
            'values'  => collect($bucket['value']['buckets'])->pluck('key')->all(),
          ];
        })
        ->filter(function ($property) use ($propertyFilters) {
          // 过滤掉只剩下一个值 或者 已经在筛选条件里的属性
          return count($property['values']) > 1 && !isset($propertyFilters[$property['key']]);
        });
    }
    //dd($properties);

    return view('products.index', [
      'products'  => $pager,
      'filters' =>  [
        'search'  => $search,
        'order' => $order,
      ],
      'category'  => $category ?? null,
      'properties'  => $properties,
      'propertyFilters' => $propertyFilters,
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

    // 商品评价
    $reviews = OrderItem::query()
      ->with(['order.user', 'productSku']) // 预先加载关联关系
      ->where('product_id', $product->id)
      ->whereNotNull('reviewed_at') // 筛选出已评价的
      ->orderBy('reviewed_at', 'desc') // 按评价时间倒序
      ->limit(10) // 取出 10 条
      ->get();



    // 最后别忘了注入到模板中
    return view('products.show', [
      'product' => $product,
      'favored' => $favored,
      'reviews' => $reviews
    ]);
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

  // 用户-收藏商品列表
  public function favorites(Request $request)
  {
    $products = $request->user()->favoriteProducts()->paginate(16);

    return view('products.favorites', ['products' => $products]);
  }
}
