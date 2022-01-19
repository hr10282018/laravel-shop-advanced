<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\ProductSku;
use App\Models\UserAddress;
use App\Models\Order;
use Carbon\Carbon;
use App\Exceptions\InvalidRequestException;
use App\Jobs\CloseOrder;    // 关闭订单队列
use Illuminate\Http\Request;

class OrdersController extends Controller
{

  // 处理用户购物车下单数据
  public function store(OrderRequest $request)
  {
    //dd($request->all());
    $user  = $request->user();

    // 开启一个数据库事务
    $order = \DB::transaction(function () use ($user, $request) {
      $address = UserAddress::find($request->input('address_id'));
      // 更新此地址的最后使用时间
      $address->update(['last_used_at' => Carbon::now()]);
      // 创建一个订单
      $order = new Order([
        'address' => [ // 将地址信息放入订单中
          'address' => $address->full_address,
          'zip' => $address->zip,
          'contact_name' => $address->contact_name,
          'contact_phone' => $address->contact_phone,
        ],
        'remark' => $request->input('remark'),
        'total_amount' => 0,
      ]);
      // 订单关联到当前用户
      $order->user()->associate($user);
      // 写入数据库
      $order->save();

      $totalAmount = 0;
      $items = $request->input('items');
      // 遍历用户提交的 SKU
      foreach ($items as $data) {
        $sku  = ProductSku::find($data['sku_id']);
        // 创建一个 OrderItem 并直接与当前订单关联
        $item = $order->items()->make([
          'amount' => $data['amount'],
          'price' => $sku->price,
        ]);
        $item->product()->associate($sku->product_id);
        $item->productSku()->associate($sku);
        $item->save();
        $totalAmount += $sku->price * $data['amount'];

        // 如果减库存返回的影响行数<=0，表示减库存失败，需抛出异常
        if ($sku->decreaseStock($data['amount']) <= 0) {
          throw new InvalidRequestException('该商品库存不足');
        }
      }

      // 更新订单总金额
      $order->update(['total_amount' => $totalAmount]);

      // 将下单的商品从购物车中移除
      $skuIds = collect($items)->pluck('sku_id');
      $user->cartItems()->whereIn('product_sku_id', $skuIds)->delete();

      return $order;
    });

    // 第一个参数是订单；第二个参数设置任务时间(在config/app.php中去定义-30分钟，如果用户没在30分钟支付则自动取消订单)
    $this->dispatch(new CloseOrder($order, config('app.order_ttl')));
    return $order;
  }

  // 订单列表
  public function index(Request $request)
  {
    $orders = Order::query()
      // 使用 with 方法预加载，避免N + 1问题
      ->with(['items.product', 'items.productSku']) // 商品、商品SKU
      ->where('user_id', $request->user()->id)
      ->orderBy('created_at', 'desc')
      ->paginate();

    return view('orders.index', ['orders' => $orders]);
  }
}
