<?php

// 自定义辅助函数


// 此方法会将当前请求的路由名称转换为 CSS 类名称，作用是允许我们针对某个页面做页面样式定制
function route_class()
{
  return str_replace('.', '-', \Route::currentRouteName());
}

// 内网穿透
function ngrok_url($routeName, $parameters = [])
{

  // 开发环境，并且配置了 NGROK_URL
  if (app()->environment('local') && $url = config('app.ngrok_url')) {
    return $url . route($routeName, $parameters, false);    // route() 函数第三个参数代表是否绝对路径
  }

  return route($routeName, $parameters);
}

// 默认的精度小数点后两位
function big_number($number, $scale = 2)
{
  return new \Moontoast\Math\BigNumber($number, $scale);
}
