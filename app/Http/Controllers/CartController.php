<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;


class CartController extends Controller
{

  // 购物车-用户添加商品
  public function add(AddCartRequest $request)
  {
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

    return [];
  }

  // 用户查看购物车
  public function index(Request $request)
  {
    // with-预加载。通过.的方式加载多层级的关联关系
    $cartItems = $request->user()->cartItems()->with(['productSku.product'])->get();

    // 用户收货地址-根据时间倒序取出
    $addresses = $request->user()->addresses()->orderBy('last_used_at', 'desc')->get();

    return view('cart.index', ['cartItems' => $cartItems, 'addresses' => $addresses]);
  }

  // 用户从购物车移除商品
  public function remove(ProductSku $sku, Request $request)
  {
    $request->user()->cartItems()->where('product_sku_id', $sku->id)->delete();

    return [];
  }



}
