<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\Category\CategoryLogic;
use App\Services\Admin\AdminLogic;
use App\Services\Users\UserLogic;

use App\Services\Order\OrderLogic;
use App\Services\Project\ProjectLogic;

use App\Http\Requests\OrderRequest;

use Redirect;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cats = CategoryLogic::getAll();
        return view('admin.orders.create',
            [
                'cats' => $cats,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        $hidden = false;
        $notify_users = false;
        $notify_admins = false;

        if($request->hidden) {
            $hidden = true;
        }
        if($request->notify_users) {
            $notify_users = true;
        }
        if($request->notify_admins) {
            $notify_admins = true;
        }

        $admins = json_decode($request->adminValues);
        $users = json_decode($request->userValues);
        $projectNames =  json_decode($request->projectValues);

        $errors = false;
        $errorArray = array();

        foreach($admins as $admin) {
            $testAdmin = AdminLogic::find($admin);
            if($testAdmin == null) {
                $errorArray[] = 'Invalid Premedia Member';
                $errors = true;
                break;
            }
        }

        foreach($users as $user) {
            $testUser = UserLogic::findUser($user);
            if($testUser == null) {
                $errorArray[] = 'Invalid Customer';
                $errors = true;
                break;
            }
        }

        if($errors) {
            return redirect()->back()->withErrors($errorArray)->withInput($request->all());
        }


        try {
            $thisOrder = OrderLogic::create($request, $hidden, $notify_admins, $notify_users);
            foreach($users as $user) {
                $thisOrder->createUser($user, $notify_users);

            }
            foreach($admins as $admin) {
                $thisOrder->createAdmin($admin, $notify_admins);
            }

        } catch(\Exception $e) {
            \Log::info($e);
            \Session::flash('flash_deleted','Failed to create order');
            return redirect('/admin/projects');
        }

        try {
            foreach($projectNames as $proj_name) {
                ProjectLogic::create($thisOrder, $proj_name);
            }

            \Session::flash('flash_created',$thisOrder->getID() . ' has been created');
            return redirect('/admin/projects');
        } catch(\Exception $e) {
            \Log::info($e);
            \Session::flash('flash_deleted','Failed to create order');
            return redirect('/admin/projects');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
