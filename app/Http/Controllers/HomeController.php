<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\Order\OrderLogic;

use App\Admin;
use App\UserAssign;
use App\Project;
use App\Order;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('user');
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

        $orders = OrderLogic::getFromUser($user);

        return view('home',
            [
                'orders' => $orders,
            ]);
    }
}
