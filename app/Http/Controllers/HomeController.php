<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Services\Order\OrderLogic;
use App\Services\Users\UserLogic;
use App\Admin;

class HomeController extends UserSideParentController
{
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

        $orders = OrderLogic::getUserProjects($user);

        return view('home',
            [
                'orders' => $orders,
                'number' => $this->val
            ]);
    }

    public function userIndex() {
        $admin = UserLogic::findUser(Auth::id());
        $oldOrders = OrderLogic::findOldUserOrders(Auth::id());
        return view('main.profile.index',
            [
                'admin' => $admin->user(),
                'oldOrders' => $oldOrders,
                'number' => $this->val
            ]
        );
    }
}
