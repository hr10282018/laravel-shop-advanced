<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
  /**
   * The URIs that should be excluded from CSRF verification.
   *
   * @var array
   */
  protected $except = [
    'payment/alipay/notify',    // 由于我们这个 URL 是给支付宝服务器调用的，肯定不会有 CSRF Token，所以需要把这个 URL 加到 CSRF 白名单里：
    'installments/alipay/notify',   // 分期还款-后端回调
  ];
}
