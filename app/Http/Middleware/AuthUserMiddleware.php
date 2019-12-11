<?php

namespace App\Http\Middleware;

use Closure;

class AuthUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        預設不允許存取
        $is_allow_access = false;
//        取得會員編號
        $user_id = session()->get('user_id');

        if (!is_null($user_id)) {
//            session 有會員編號，允許存取
            $is_allow_access = true;
        }

        if (!$is_allow_access) {
//            若不允許存取，重新導向登入頁
            return redirect()->to('/user/sign-in');
        }
        return $next($request);
    }
}
