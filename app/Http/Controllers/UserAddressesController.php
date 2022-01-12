<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Http\Requests\UserAddressRequest;


class UserAddressesController extends Controller
{

  // 显示收货地址列表
  public function index(Request $request)
  {
    // 把当前用户下的所有地址作为变量$addresses注入到模板 user_addresses.index 中并渲染
    return view('user_addresses.index', [
      'addresses' => $request->user()->addresses,
    ]);
  }

  // 新建收货地址页面
  public function create()
  {
    return view('user_addresses.create_and_edit', ['address' => new UserAddress()]);
  }

  // 处理提交的数据
  public function store(UserAddressRequest $request)
  {
    // 获取当前用户->与地址的关联关系->在关联关系里创建一个新的记录
    $request->user()->addresses()->create($request->only([  // only-白名单
      'province',
      'city',
      'district',
      'address',
      'zip',
      'contact_name',
      'contact_phone',
    ]));

    return redirect()->route('user_addresses.index');
  }
}
