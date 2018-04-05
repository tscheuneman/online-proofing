<?php

namespace App\Http\Controllers;

use App\Project;
use App\Categories;
use App\Admin;
use App\User;
use App\UserAssign;
use App\AdminAssign;

use Illuminate\Http\Request;

use Validator;
use Redirect;
use Storage;
use File;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project = Project::with('category', 'users.user', 'admins.admin.userSearch', 'admin_entries')->get();

        return view('admin.projects.index',
            [
                'projects' => $project,
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
        $cats = Categories::get();
        return view('admin.projects.create',
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
            'name' => 'required|string',
            'category' => 'required|exists:categories,id',
            'adminValues' => 'required|json',
            'userValues' => 'required|json'
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
        $rand = str_random(12);
        $path = date('Y') . '/' . date('F') . '/' . $rand;

        if(File::makeDirectory(public_path('storage/projects/' . $path), 0777, true)) {

            $project = new Project();
                $project->project_name = $request->name;
                $project->file_path = $rand;
                $project->cat_id = $request->category;
                $project->hidden = $hidden;
                $project->notify_users = $notify_users;
                $project->notify_admins = $notify_admins;
                $project->save();

            foreach($users as $user) {
                $assign = new UserAssign();
                    $assign->user_id = $user;
                    $assign->project_id = $project->id;
                    $assign->notify = $notify_users;
                    $assign->save();
            }
            foreach($admins as $admin) {
                $assign = new AdminAssign();
                $assign->user_id = $admin;
                $assign->project_id = $project->id;
                $assign->notify = $notify_admins;
                $assign->save();
            }

            \Session::flash('flash_created',$project->project_name . ' has been created');
            return redirect('/admin/projects');
        }


        \Session::flash('flash_deleted','Failed to create project');
        return redirect('/admin/projects');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }
}
