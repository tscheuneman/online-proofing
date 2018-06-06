<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

use App\Services\Users\UserLogic;

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
        \Log::info(Auth::user());
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
