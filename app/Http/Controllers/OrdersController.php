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
use App\Services\CartService; // 封装代码类-购物车
use App\Services\OrderService;
use App\Http\Requests\SendReviewRequest;
use App\Events\OrderReviewed;   // 事件-用户评分
use App\Http\Requests\ApplyRefundRequest; // 图款请求
use App\Exceptions\CouponCodeUnavailableException;
use App\Models\CouponCode;

class OrdersController extends Controller
{

  /***
  处理用户购物车下单数据
  public function store(OrderRequest $request, CartService $cartService) // 利用Laravel的自动解析功能注入 CartService 类
  {
    //dd($request->all());
    $user  = $request->user();

    // 开启一个数据库事务
    $order = \DB::transaction(function () use ($user, $request, $cartService) { //把 $cartService 加入use中
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


      {{
        将下单的商品从购物车中移除
        $skuIds = collect($items)->pluck('sku_id');
        $user->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
      }}
      // 封装以上{{}}中的代码
      $skuIds = collect($request->input('items'))->pluck('sku_id')->all();
      $cartService->remove($skuIds);

      return $order;
    });

    // 第一个参数是订单；第二个参数设置任务时间(在config/app.php中去定义-30分钟，如果用户没在30分钟支付则自动取消订单)
    $this->dispatch(new CloseOrder($order, config('app.order_ttl')));
    return $order;
  }
   */

  // 处理用户购物车下单-封装以上的store()
  public function store(OrderRequest $request, OrderService $orderService)
  {
    $user    = $request->user();
    $address = UserAddress::find($request->input('address_id'));

    $coupon  = null;

    // 如果用户提交了优惠码
    if ($code = $request->input('coupon_code')) {
      $coupon = CouponCode::where('code', $code)->first();
      if (!$coupon) {
        throw new CouponCodeUnavailableException('优惠券不存在');
      }
    }
    // 参数中加入 $coupon 变量
    return $orderService->store($user, $address, $request->input('remark'), $request->input('items'), $coupon);
  }


  // 订单列表页
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
  // 订单详情页
  public function show(Order $order, Request $request)
  {
    // 用户权限-查看自己的订单
    $this->authorize('own', $order);

    // load() 方法与 with()预加载方法有些类似，称为 延迟预加载
    return view('orders.show', ['order' => $order->load(['items.productSku', 'items.product'])]);
  }

  // 用户确认收货
  public function received(Order $order, Request $request)
  {
    // 校验权限
    $this->authorize('own', $order);

    // 判断订单的发货状态是否为已发货
    if ($order->ship_status !== Order::SHIP_STATUS_DELIVERED) {
      throw new InvalidRequestException('发货状态不正确');
    }

    // 更新发货状态为已收到(确认收货)
    $order->update(['ship_status' => Order::SHIP_STATUS_RECEIVED]);

    // 返回订单信息(ajax请求)
    return $order;
  }

  // 用户-评价页面
  public function review(Order $order)
  {
    // 校验权限
    $this->authorize('own', $order);
    // 判断是否已经支付
    if (!$order->paid_at) {
      throw new InvalidRequestException('该订单未支付，不可评价');
    }
    // 使用 load 方法加载关联数据，避免 N + 1 性能问题
    return view('orders.review', ['order' => $order->load(['items.productSku', 'items.product'])]);
  }
  // 用户-处理提交评价
  public function sendReview(Order $order, SendReviewRequest $request)
  {
    // 校验权限
    $this->authorize('own', $order);
    if (!$order->paid_at) {
      throw new InvalidRequestException('该订单未支付，不可评价');
    }
    // 判断是否已经评价
    if ($order->reviewed) {
      throw new InvalidRequestException('该订单已评价，不可重复提交');
    }
    $reviews = $request->input('reviews');
    // 开启事务
    \DB::transaction(function () use ($reviews, $order) {
      // 遍历用户提交的数据
      foreach ($reviews as $review) {
        $orderItem = $order->items()->find($review['id']);
        // 保存评分和评价
        $orderItem->update([
          'rating'      => $review['rating'],
          'review'      => $review['review'],
          'reviewed_at' => Carbon::now(),
        ]);
      }
      // 将订单标记为已评价
      $order->update(['reviewed' => true]);
    });
    event(new OrderReviewed($order));   // 用户评分事件(触发)

    return redirect()->back();
  }


  // 用户-处理退款
  public function applyRefund(Order $order, ApplyRefundRequest $request)
  {
    // 校验订单是否属于当前用户
    $this->authorize('own', $order);
    // 判断订单是否已付款
    if (!$order->paid_at) {
      throw new InvalidRequestException('该订单未支付，不可退款');
    }
    // 判断订单退款状态是否正确
    if ($order->refund_status !== Order::REFUND_STATUS_PENDING) {
      throw new InvalidRequestException('该订单已经申请过退款，请勿重复申请');
    }
    // 将用户输入的退款理由放到订单的 extra 字段中
    $extra                  = $order->extra ?: [];
    $extra['refund_reason'] = $request->input('reason');
    // 将订单退款状态改为已申请退款
    $order->update([
      'refund_status' => Order::REFUND_STATUS_APPLIED,
      'extra'         => $extra,
    ]);

    return $order;
  }
}
