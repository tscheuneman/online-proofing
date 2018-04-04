<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;

use Validator;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('org', '!=', 'Admin')->get();
        return view('admin.users.index',
            [
                'users' => $user,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'org' => 'required|string'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $pwReturn = str_random(12);

        $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->org = $request->org;
            $user->email = $request->email;
            $user->name = $request->email;
            $user->password = Hash::make($pwReturn);
            $user->active = false;
            $user->save();

        Mail::to($request->email)->send(new UserCreated($user, $pwReturn));

        \Session::flash('flash_created','Account for' . $user->first_name . ' has been created');
        return redirect('/admin/customers');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }

    public function password() {
        $user = Auth::user();
        if(!$user->active) {
            if (Auth::check()) {
                return view('main.users.password',
                    [
                        'user' => $user,
                    ]
                );
            }
            return redirect('/login');
        }
        return redirect('/admin');
    }

    public function passwordSave(Request $request)
    {
        $theUser = Auth::user();
        if (!$theUser->active) {
            if (Auth::check()) {
                $rules = array(
                    'password' => 'required|string|min:6|confirmed',
                    'user_id' => 'required|exists:users,id',
                );

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput($request->all());
                }

                if ($request->user_id == Auth::id()) {
                    $user = User::find($request->user_id);
                    if ($user != null) {
                        $user->password = Hash::make($request->password);
                        $user->active = true;
                        $user->save();

                        return redirect('/');
                    }
                }

                return redirect()->back()->withErrors('Failed');

            }
            return redirect('/login');
        }
        return redirect('/');
    }


}
