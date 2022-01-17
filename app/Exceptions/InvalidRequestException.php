<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class InvalidRequestException extends Exception
{
  public function __construct(string $message = "", int $code = 400)
  {
    parent::__construct($message, $code);
  }

  // Laravel 5.5 之后支持在异常类中定义 render() 方法，该异常被触发时系统会调用 render() 方法来输出
  public function render(Request $request)
  {
    if ($request->expectsJson()) {  // 判断如果是AJAX请求，则返回JSON格式数据
      // json() 方法第二个参数就是 Http 返回码
      return response()->json(['msg' => $this->message], $this->code);
    }

    return view('pages.error', ['msg' => $this->message]);  // 如果不是AJAX请求，则返回页面
  }

}
