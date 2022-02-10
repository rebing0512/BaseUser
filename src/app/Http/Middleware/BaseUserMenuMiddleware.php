<?php

namespace Jenson\BaseUser\Middleware;

use Closure;
use Illuminate\Http\Request;

class BaseUserMenuMiddleware
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
        //dd(config('mbcore_baseuser.baseuser_development'));
        if (config('mbcore_baseuser.baseuser_development')) {
            //进入下一层请求
            return $next($request);
        } else {
            if(config('mbcore_baseuser.baseuser_homeRoute'))
                $adminHomeRoute= config('mbcore_baseuser.baseuser_homeRoute');
            else
                $adminHomeRoute= 'user.home';
            //跳转到首页
            return redirect()->route($adminHomeRoute);
        }
    }
}
