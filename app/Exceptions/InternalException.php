<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class InternalException extends Exception
{

  protected $msgForUser;

  /*
    第一个参数是原本的异常信息
    第二个参数是返回给用户看的信息
  */
  public function __construct(string $message, string $msgForUser = '系统内部错误', int $code = 500)
  {
    parent::__construct($message, $code);
    $this->msgForUser = $msgForUser;
  }

  public function render(Request $request)
  {
    // 返回给用户-系统内部错误信息
    if ($request->expectsJson()) {
      return response()->json(['msg' => $this->msgForUser], $this->code);
    }

    return view('pages.error', ['msg' => $this->msgForUser]);
  }
}
