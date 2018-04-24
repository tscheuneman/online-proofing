<?php

namespace App\Services\Order;

use App\Order;
use App\AdminAssign;
use App\UserAssign;

class OrderLogic {

    protected $order;

    /**
     * OrderLogic Constructor
     *
     * @param \App\Order $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get all projects assigned to a given user
     *
     * @param string $id
     * @return \App\Order[] $userProjects
     */
    public static function getUserProjects($id) {
        $userProjects = Order::whereHas('users.user', function($query) use($id) {
            $query->where('id', '=', $id);
        })->whereHas('admin_projects', function($query2) {
            $query2->with('entries.user')->where('projects.completed', false)->where('active', true);
        })->with(array('projects' => function($q) {
            $q->with('admin_entries')->where('projects.completed', false)->where('active', true);
        }))->get();

        return $userProjects;
    }

    /**
     * Get all completed orders for a given user
     *
     * @param string $id
     * @return \App\Order[] $userProjects
     */
    public static function findOldUserOrders($id) {
        $userProjects = Order::whereHas('users.user', function($query) use($id) {
            $query->where('id', '=', $id);
        })->whereHas('admin_projects', function($query2) {
            $query2->with('entries.user')->where('projects.completed', false)->where('active', true);
        })->with(array('projects' => function($q) {
            $q->with('admin_entries')->where('projects.completed', true)->where('active', true);
        }))->get();

        return $userProjects;
    }


    /**
     * Get all projects assigned to a given Admin
     *
     * @param string $id
     * @return \App\Order[] $userProjects
     */
    public static function getAdminProjects($id) {
        $userProjects = Order::whereHas('admins.admin.user', function($query) use($id) {
            $query->where('id', $id);
        })->whereHas('admin_projects', function($query2) {
            $query2->with('entries.user');
        })->get();

        return $userProjects;
    }

    /**
     * Get all projects assigned not assigned to an admin
     *
     * @param string $id
     * @return \App\Order[] $userProjects
     */
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

    /**
     * Get all projects that aren't closed
     *
     * @return \App\Order[] $userProjects
     */

    public static function getNonClosedProjects() {
        $userProjects = Order::whereHas('projects', function($query2) {
            $query2->with('entries.user')->where('projects.completed', false);
        })->get();

        return $userProjects;
    }

    /**
     * Create an order
     *
     * @param \Illuminate\Http\Request $request
     * @param boolean $hidden
     * @param boolean $notify_admins
     * @param boolean $notify_users
     * @return \App\Services\Order\OrderLogic $order
     */
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

    /**
     * Create an admin assigned to an order
     *
     * @param string $user_id
     * @param boolean $notify
     * @return void
     */
    public function createAdmin($user_id, $notify) {
        $assign = new AdminAssign();
        $assign->user_id = $user_id;
        $assign->order_id = $this->order->id;
        $assign->notify = $notify;
        $assign->save();
    }

    /**
     * Create a user assigned to an order
     *
     * @param string $user_id
     * @param boolean $notify
     * @return void
     */
    public function createUser($user_id, $notify) {
        $assign = new UserAssign();
        $assign->user_id = $user_id;
        $assign->order_id = $this->order->id;
        $assign->notify = $notify;
        $assign->save();
    }

    /**
     * Get the Order ID
     *
     * @return string
     */
    public function getID() {
        return $this->order->id;
    }

    /**
     * Find an order
     *
     * @param string $id
     * @return \App\Services\Order\OrderLogic
     */
    public static function find($id) {
        $order = Order::find($id);

        return new OrderLogic($order);
    }

    /**
     * Get other products inside of an order with a given id
     *
     * @param string $id
     * @return \App\Order
     */
    public function getOtherProducts($id) {
        $order = $this->order->where('id', $this->order->id)->with(array('projects' => function($q) use($id) {
            $q->with('admin_entries')->where('id', '!=', $id);
        }))->first();

        return $order;
    }
}

?>