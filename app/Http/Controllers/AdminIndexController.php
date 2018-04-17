<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Admin;
use App\User;
use App\Project;
use App\Entry;
use App\Order;

class AdminIndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = Admin::where('user_id', '=', Auth::id())->with('user')->first();
        $user_count = User::where('deleted_at', '=', null)->where('org', '!=', 'Admin')->count();
        $proj_count = Project::where('active', '=', true)->where('completed', '=', false)->count();

        $userProjects = Order::whereHas('admins.admin.user', function($query) {
            $query->where('id', Auth::id());
        })->with(array('projects' => function($query2) {
            $query2->with('entries.user')->where('projects.completed', false)->where('active', true);
        }))->get();
        
        return view('admin.index',
            [
                'admin' => $admin,
                'user_count' => $user_count,
                'proj_count' => $proj_count,
                'userProjects' => $userProjects
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
