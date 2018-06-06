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
        else {
            if(cas()->isAuthenticated()) {
                $email = cas()->user() . '@' . ENV('CAS_APPEND');
                $user = UserLogic::checkUserCAS($email);
                if($user) {
                    Auth::login($user->user());

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
            return redirect('/login');
        }

    }
}
