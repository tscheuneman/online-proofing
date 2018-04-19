<?php

namespace App\Services\Order;

use App\Order;
use App\AdminAssign;
use App\UserAssign;

class OrderLogic {

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public static function getUserProjects($id) {
        $userProjects = Order::with('users.user', 'projects.entries.user')->whereHas('users.user', function($query) use($id) {
            $query->where('id', $id);
        })->whereHas('projects', function($query2) {
            $query2->with('entries.user')->where('projects.completed', false)->where('active', true);
        })->get();

        return $userProjects;
    }

    public static function getAdminProjects($id) {
        $userProjects = Order::whereHas('admins.admin.user', function($query) use($id) {
            $query->where('id', $id);
        })->whereHas('admin_projects', function($query2) {
            $query2->with('entries.user');
        })->with(array('projects' => function($q) {
            $q->where('completed', false);
        }))->get();

        return $userProjects;
    }

    public static function getOtherAdminProjects($id) {
        $userProjects = Order::whereHas('admins.admin.user', function($query) use($id) {
            $query->where('id', '!=', $id);
        })->whereHas('admin_projects', function($query2) {
            $query2->with('entries.user');
        })->with(array('projects' => function($q) {
            $q->where('completed', false);
        }))->get();

        return $userProjects;
    }

    public static function getNonClosedProjects() {
        $userProjects = Order::whereHas('projects', function($query2) {
            $query2->with('entries.user')->where('projects.completed', false);
        })->get();

        return $userProjects;
    }

    public static function create($request, $hidden, $notify_admins, $notify_users) {
        try {
            $order = new Order();
                $order->job_id = $request->job_id;
                $order->cat_id = $request->category;
                $order->hidden = $hidden;
                $order->notify_users = $notify_users;
                $order->notify_admins = $notify_admins;
                $order->save();

            return new OrderLogic($order);

        } catch(\Exception $e) {
            return false;
        }
    }

    public function createAdmin($user_id, $notify) {
        $assign = new AdminAssign();
        $assign->user_id = $user_id;
        $assign->order_id = $this->order->id;
        $assign->notify = $notify;
        $assign->save();
    }

    public function createUser($user_id, $notify) {
        $assign = new UserAssign();
        $assign->user_id = $user_id;
        $assign->order_id = $this->order->id;
        $assign->notify = $notify;
        $assign->save();
    }

    public function getID() {
        return $this->order->id;
    }
}

?>