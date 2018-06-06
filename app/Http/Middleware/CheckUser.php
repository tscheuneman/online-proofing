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
            $this->checkUser($request, $next);
        }
        else {
            if(cas()->checkAuthentication()) {
                $email = cas()->user() . '@' . ENV('CAS_APPEND');
                $user = UserLogic::checkUserCAS($email);
                if($user) {
                    Auth::login($user->user());
                    $this->checkUser($request, $next);
                }
            }
        }
        return redirect('/login');
    }

    public function checkUser($request, Closure $next) {
        $user = UserLogic::findUser(Auth::id());
        if($user) {
            if($user->returnActive()) {
                return $next($request);
            }
            return redirect('/password');
        }
        return redirect('/');
    }
}
