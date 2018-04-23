<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Http\Requests\PasswordRequest;

use App\Services\Admin\AdminLogic;
use App\Services\Users\UserLogic;

use Validator;
use Redirect;



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
        $admins = AdminLogic::getAll();
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
    public function store(AdminRequest $request)
    {
        $user = UserLogic::createUser($request, "Admin");
        AdminLogic::createAdmin($user);

        \Session::flash('flash_created','Account has been created');
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
        if (Auth::check()) {
            $user = Auth::user();
            $admin = AdminLogic::findAdmin($user->id);
            if (!$admin->isActive()) {
                    return view('admin.admins.password',
                        [
                            'admin' => $user,
                        ]
                    );
            }
            return redirect('/admin');
        }
        return redirect('/login');
    }

    public function passwordSave(PasswordRequest $request)
    {
        $theUser = Auth::user();
        $admin = AdminLogic::findAdmin($theUser->id);
        if (!$admin->isActive()) {
            if (Auth::check()) {
                if ($request->user_id == Auth::id()) {
                        if($user = UserLogic::findUser($request->user_id)) {
                            $user->savePassword($request->password);

                            if($admin = AdminLogic::findFromUser($user)) {
                                $admin->makeActive();
                                return redirect('/admin');
                            }
                            return redirect()->back()->withErrors('Failed');

                        }
                    return redirect()->back()->withErrors('Failed');
                }

                return redirect()->back()->withErrors('Failed');

            }
            return redirect('/login');
        }
        return redirect('/admin');
    }
}
