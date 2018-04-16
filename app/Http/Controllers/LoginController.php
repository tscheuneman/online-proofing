<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Services\Users\UserLogic;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {

        $rules = array(
            'email' => 'required|email',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $explodedEmail = explode('@', $request->email);
        $domain = array_pop($explodedEmail);



        if($domain == ENV('CAS_APPEND')) {
            if(cas()->checkAuthentication()) {
                $email = cas()->user() . '@' . ENV('CAS_APPEND');
                $user = UserLogic::checkUserCAS($email);
                if($user) {
                    Auth::login($user->user());
                    return redirect('/');
                }
                return redirect()->back()->withErrors(array('Invalid User'));
            }
            else {
                cas()->authenticate();
            }
            return $domain;
        }
        elseif($request->email && $request->password) {
            $credentials = $request->only('email', 'password');
            if(Auth::attempt($credentials)) {
                return redirect()->intended('/');
            }
            return redirect('login');
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
        Auth::logout();
        return redirect('login');
    }
}
