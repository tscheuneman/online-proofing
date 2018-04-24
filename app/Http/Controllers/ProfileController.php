<?php

namespace App\Http\Controllers;

use App\Services\Activity\ActivityLogic;
use App\Services\Order\OrderLogic;
use Illuminate\Http\Request;
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

    public function userIndex() {
        $admin = User::find(Auth::id());
        $oldOrders = OrderLogic::findOldUserOrders(Auth::id());
        return view('main.profile.index',
            [
                'admin' => $admin,
                'oldOrders' => $oldOrders
            ]
        );
    }
}
