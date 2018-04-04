<?php

namespace App\Http\Controllers;

use App\Admin;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Redirect;

use Illuminate\Support\Facades\Mail;
use App\Mail\AdminCreated;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = Admin::with('user')->get();
        return view('admin.admins.index',
            [
                'admins' => $admins,
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
        return view('admin.admins.create');
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
            'email' => 'required|email|unique:users'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $pwReturn = str_random(12);


        $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->org = "Admin";
            $user->email = $request->email;
            $user->name = $request->email;
            $user->password = Hash::make($pwReturn);
            $user->active = true;
            $user->save();

        $admin = new Admin();
            $admin->user_id = $user->id;
            $admin->save();

        Mail::to($request->email)->send(new AdminCreated($user, $pwReturn));

        \Session::flash('flash_created','Account for' . $user->first_name . ' has been created');
        return redirect('/admin/users');
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
            $admin = Admin::where('user_id', '=', $user->id)->first();
        if(!$admin->active) {
            if (Auth::check()) {
                return view('admin.admins.password',
                    [
                        'admin' => $user,
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
            $admin = Admin::where('user_id', '=', $theUser->id)->first();
        if (!$admin->active) {
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
                        $user->save();

                        $adminMain = Admin::where('user_id', '=', $request->user_id)->first();
                        $adminMain->active = true;
                        $adminMain->save();
                        return redirect('/admin');
                    }
                }

                return redirect()->back()->withErrors('Failed');

            }
            return redirect('/login');
        }
        return redirect('/admin');
    }
}
