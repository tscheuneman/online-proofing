<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;
use App\Categories;
use App\Admin;
use App\User;
use App\UserAssign;
use App\AdminAssign;
use App\Order;


use Validator;
use Redirect;
use Storage;
use File;


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
        $cats = Categories::get();
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
    public function store(Request $request)
    {
        $rules = array(
            'job_id' => 'required|string|unique:orders,job_id',
            'category' => 'required|exists:categories,id',
            'adminValues' => 'required|json',
            'userValues' => 'required|json',
            'projectValues' => 'required|json'
        );
        $hidden = false;
        $notify_users = false;
        $notify_admins = false;

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
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
            $testAdmin = Admin::find($admin);
            if($testAdmin == null) {
                $errorArray[] = 'Invalid Premedia Member';
                $errors = true;
                break;
            }
        }
        foreach($users as $user) {
            $testUser = User::find($user);
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
            $order = new Order();
            $order->job_id = $request->job_id;
            $order->cat_id = $request->category;
            $order->hidden = $hidden;
            $order->notify_users = $notify_users;
            $order->notify_admins = $notify_admins;
            $order->save();

            foreach($users as $user) {
                $assign = new UserAssign();
                $assign->user_id = $user;
                $assign->order_id = $order->id;
                $assign->notify = $notify_users;
                $assign->save();
            }
            foreach($admins as $admin) {
                $assign = new AdminAssign();
                $assign->user_id = $admin;
                $assign->order_id = $order->id;
                $assign->notify = $notify_admins;
                $assign->save();
            }

        } catch(\Exception $e) {
            \Log::info($e);
            \Session::flash('flash_deleted','Failed to create order');
            return redirect('/admin/projects');
        }

        try {
            foreach($projectNames as $proj_name) {
                $rand = str_random(12);
                $path = date('Y') . '/' . date('F') . '/' . $rand;

                if(File::makeDirectory(public_path('storage/projects/' . $path), 0775, true)) {
                    $project = new Project();
                    $project->project_name = $proj_name;
                    $project->file_path = $rand;
                    $project->ord_id = $order->id;
                    $project->save();
                }
            }

            \Session::flash('flash_created',$order->job_id . ' has been created');
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
