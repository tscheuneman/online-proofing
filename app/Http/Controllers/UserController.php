<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UserRequest;
use App\Http\Requests\PasswordRequest;

use Illuminate\Support\Facades\Auth;

use App\Services\Users\UserLogic;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = UserLogic::getAll();
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
    public function store(UserRequest $request)
    {

        UserLogic::createUser($request, $request->org);

        \Session::flash('flash_created','Account for has been created');
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
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->active) {
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
        return redirect('/login');
    }

    public function passwordSave(PasswordRequest $request)
    {
        $theUser = Auth::user();
        if (!$theUser->active) {
            if (Auth::check()) {

                if ($request->user_id == Auth::id()) {
                    $user = UserLogic::findUser($request->user_id);
                    $user->savePassword($request->password);
                        return redirect('/');
                }

                return redirect()->back()->withErrors('Failed');

            }
            return redirect('/login');
        }
        return redirect('/');
    }


}
