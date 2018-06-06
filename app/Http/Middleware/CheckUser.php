<?php

namespace App\Http\Middleware;

use Closure;

use App\Services\Users\UserLogic;

use Illuminate\Support\Facades\Auth;


class CheckUser
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
        if (Auth::check()) {
            $user = UserLogic::findUser(Auth::id());
            if($user) {
                if($user->returnActive()) {
                    return $next($request);
                }
                return redirect('/password');
            }
            return redirect('/');
        }
        return redirect('/login');

    }
}
