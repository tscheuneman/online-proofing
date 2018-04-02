<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Admin;

class CheckAdmin
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
            $findAdmin = Admin::where('user_id', '=', Auth::id())->first();
            if($findAdmin !== null) {
                if($findAdmin->active) {
                    return $next($request);
                }
                return redirect('/admin/password');

            }
            return redirect('/');
        }
        return redirect('/login');
    }
}
