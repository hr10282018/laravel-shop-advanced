<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;
use App\Services\CartService; // 封装代码类-购物车

class CartController extends Controller
{
  protected $cartService;

  // 利用 Laravel 的自动解析功能注入 CartService类(封装购物车代码)
  public function __construct(CartService $cartService)
  {
      $this->cartService = $cartService;
  }


  // 购物车-用户添加商品
  public function add(AddCartRequest $request)
  {
    /*
    $user   = $request->user();
    $skuId  = $request->input('sku_id');
    $amount = $request->input('amount');

    // 从数据库中查询该商品是否已经在购物车中
    if ($cart = $user->cartItems()->where('product_sku_id', $skuId)->first()) {

      // 如果存在则直接叠加商品数量
      $cart->update([
        'amount' => $cart->amount + $amount,
      ]);
    } else {

      // 否则创建一个新的购物车记录
      $cart = new CartItem(['amount' => $amount]);
      $cart->user()->associate($user);  // associate-该方法会设置子模型的外键
      $cart->productSku()->associate($skuId);
      $cart->save();
    }
    */

    // 上面代码已封装
    $this->cartService->add($request->input('sku_id'), $request->input('amount'));
    return [];
  }

  // 用户查看购物车
  public function index(Request $request)
  {
    /*
    with-预加载。通过.的方式加载多层级的关联关系
    $cartItems = $request->user()->cartItems()->with(['productSku.product'])->get();
    */
    $cartItems = $this->cartService->get(); // 封装以上代码

    // 用户收货地址-根据时间倒序取出
    $addresses = $request->user()->addresses()->orderBy('last_used_at', 'desc')->get();

    return view('cart.index', ['cartItems' => $cartItems, 'addresses' => $addresses]);
  }

  // 用户从购物车移除商品
  public function remove(ProductSku $sku, Request $request)
  {
    // $request->user()->cartItems()->where('product_sku_id', $sku->id)->delete();

    $this->cartService->remove($sku->id);  // 封装以上代码
    return [];
  }



}
