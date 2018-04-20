<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UserRequest;
use App\Http\Requests\PasswordRequest;

use Illuminate\Support\Facades\Auth;

use App\Services\Users\UserLogic;

use App\Jobs\ProfileImage;

use Validator;

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
     * Upload an image
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function image(Request $request) {
        $rules = array(
            'user_id' => 'required|exists:users,id',
            'files' => 'required|image|max:5000',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            \Session::flash('flash_deleted','Failed to save');
            return redirect()->back();
        }

        try {
            $path = $request->file('files')->store(
                'pictures/', 'public'
            );
        }
        catch(Exception $e) {
            \Session::flash('flash_deleted','Error uploading file');
            return redirect()->back();
        }

        $user = UserLogic::findUser($request->user_id);
            if($user->saveFile($path)) {

                ProfileImage::dispatch($path, 500, 60);
                \Session::flash('flash_created','Save profile image');
                return redirect()->back();
            }
        \Session::flash('flash_deleted','Error uploading file');
        return redirect()->back();
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

    /**
     * Remove the specified resource from storage.
     *
     * @return mixed
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\PasswordRequest $request
     * @return \Illuminate\Http\Response
     */
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
