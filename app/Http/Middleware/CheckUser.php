<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

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
            $user = User::where('id', '=', Auth::id())->first();
            if($user !== null) {
                if($user->active) {
                    return $next($request);
                }
                return redirect('/password');
            }
            return redirect('/');
        }
        return redirect('/login');
    }
}
