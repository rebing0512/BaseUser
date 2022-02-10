<?php

namespace Jenson\BaseUser\Middleware;

use Closure;
use Illuminate\Http\Request;

class BaseUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //通过session来检测用户是否登录
        if ($request->session()->has('user.is_login')) {
            //进入下一层请求
            return $next($request);
         } else {
            return redirect()->route('user.login.login');
          }
    }
}
