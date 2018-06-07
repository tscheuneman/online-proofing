<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use Illuminate\Support\Facades\Auth;
use Session;
use Cookie;

use App\Services\Users\UserLogic;


class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.login');
    }

    public function casLogin() {
        if(cas()->isAuthenticated()) {
            $email = cas()->user() . '@' . ENV('CAS_APPEND');
            $user = UserLogic::checkUserCAS($email);
            if ($user) {
                Auth::login($user->user());
                return redirect('/');
            }
        }
        return redirect('/login');
    }

    public function store(Request $request)
    {

        $rules = array(
            'email' => 'required|email|exists:users',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $explodedEmail = explode('@', $request->email);
        $domain = array_pop($explodedEmail);


        if($domain == ENV('CAS_APPEND')) {
            if(cas()->isAuthenticated()) {
                if($explodedEmail[0] == cas()->user()) {
                    $this->casLogin();
                }
                return redirect('/');
            }
            else {
                cas()->authenticate();
            }
        }
        elseif($request->email && $request->password) {
            $credentials = $request->only('email', 'password');
            if(Auth::attempt($credentials)) {
                return redirect()->intended('/');
            }
            return view('auth.loginTwo', [
                'username' => $request->email
            ])->withInput($request->email)->withErrors(array('password' => 'Invalid Password'));
            //return redirect()->back()->withErrors(array('password' => 'Invalid Password'));
        }
        else {
            return view('auth.loginTwo', [
                'username' => $request->email
            ]);
        }

        \Session::flash('flash_created','Category has been created');
        return redirect('/admin/categories');
    }

    public function logout() {

        $user = Auth::user();

        $explodedEmail = explode('@', $user->email);
        $domain = array_pop($explodedEmail);

        Session::flush();
        Auth::logout();

        Cookie::queue(Cookie::forget('CASAuth'));

        if($domain == ENV('CAS_APPEND')) {
            return redirect('/logout/cas');
        }
        return redirect('/login');
    }

    public function logoutCas() {
        cas()->logout();
    }

}
