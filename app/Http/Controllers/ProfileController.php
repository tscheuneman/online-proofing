<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class ProfileController extends Controller
{
    public function index()
    {

        $admin = User::find(Auth::id());
        return view('admin.profile.index',
            [
                'admin' => $admin,
            ]
        );
    }
}
