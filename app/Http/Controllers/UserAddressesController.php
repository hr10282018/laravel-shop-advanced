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
  // 处理新建收货地址提交的数据
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

  // 修改收货地址信息
  public function edit(UserAddress $user_address) // 此处user_address对应路由的{user_address},需要一致
  {
    /*
      授权代码：
      authorize方法会获取第二个参数 $user_address 的类名(App\Models\UserAddress)，
      则对应App\Policies\UserAddressPolicy,之后实例化该类,调用own方法，来判断权限。
    */
    $this->authorize('own', $user_address); // 授权

    //dd($user_address);

    return view('user_addresses.create_and_edit', ['address' => $user_address]);
  }
  // 处理修改收货地址
  public function update(UserAddress $user_address, UserAddressRequest $request)
  {
    $user_address->update($request->only([
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

  // 删除收货地址信息
  public function destroy(UserAddress $user_address)
  {
    $user_address->delete();

    //return redirect()->route('user_addresses.index');
    return [];
  }
}
