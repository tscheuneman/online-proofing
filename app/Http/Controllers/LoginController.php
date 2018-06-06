<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Services\Users\UserLogic;

use Auth;


class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
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
            if(cas()->authenticate()) {
                $email = cas()->user() . '@' . ENV('CAS_APPEND');
                return $email;
                $user = UserLogic::checkUserCAS($email);
                if($user) {
                    Auth::loginUsingId($user->returnID());
                    return redirect('/');
                }
                return redirect()->back()->withErrors(array('password' => 'CAS user does not exist'));
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
        Auth::logout();
        return redirect('login');
    }
}
