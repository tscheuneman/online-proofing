<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use App\User;
use App\Project;

class SearchController extends Controller
{
    public function findAdmin(Request $request)
    {
        return Admin::search($request->get('q'))->with('userSearch')->get(['id', 'user_id']);
    }
    
    public function findUser(Request $request)
    {
        return User::search($request->get('q'))->where('org', '!=', 'Admin')->get(['id', 'first_name', 'last_name', 'email']);
    }

    public function findProjects(Request $request)
    {
        return Project::with('order_name.admins.admin.search_user', 'order_name.users.search_user', 'inital_image')->search($request->get('q'))->get(['id', 'file_path', 'ord_id', 'project_name', 'created_at', 'active', 'completed'])->take(10);
    }
}
