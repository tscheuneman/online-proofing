<?php

namespace App\Http\Middleware;

use Closure;

use App\Services\Users\UserLogic;

use Auth;

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
            if(cas()->checkAuthentication()) {
                $email = cas()->user() . '@' . ENV('CAS_APPEND');
                $user = UserLogic::checkUserCAS($email);
                if($user) {
                    Auth::loginUsingId($user->returnID());

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
