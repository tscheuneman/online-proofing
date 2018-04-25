<?php

namespace App\Http\Controllers;

use App\Services\Activity\ActivityLogic;
use Illuminate\Support\Facades\Auth;
use App\User;

class ProfileController extends Controller
{
    public function index()
    {

        $admin = User::find(Auth::id());
        $activity = ActivityLogic::getAllFromUser(Auth::id());

        return view('admin.profile.index',
            [
                'admin' => $admin,
                'activity' => $activity
            ]
        );
    }

}
