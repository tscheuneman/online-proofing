<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Services\Admin\AdminLogic;

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
            $findAdmin = AdminLogic::findAdmin(Auth::id());
            if($findAdmin) {
                if($findAdmin->isActive()) {
                    return $next($request);
                }
                return redirect('/admin/password');
            }
            return redirect('/');
        }
        return redirect('/login');
    }
}
