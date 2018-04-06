<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Admin;
use App\UserAssign;
use App\Project;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::id();
        $admin = Admin::where('user_id', '=', $user)->first();
        if($admin != null) {
            return redirect('/admin');
        }
        $projects = Project::with(array('users.user' => function($query)
        {
            $query->where('id', '=', Auth::id());
        }))->with('admin_entries')->get();
        return $projects;

        return view('home');
    }
}
