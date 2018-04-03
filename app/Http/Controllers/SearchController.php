<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use App\User;

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
}
