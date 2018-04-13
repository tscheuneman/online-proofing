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

    public static function getFromUser($id) {
        $orders = Order::with('users.user', 'projects')->get();

        $returnArray = array();
        $orderCnt = 0;

        foreach($orders as $ord) {
            $projectCount = 0;
            $returnProjects = array();
            $isUsers = false;
            foreach($ord->projects as $proj) {
                if($proj->active){
                    $returnProjects[] = $proj;
                    $projectCount++;
                }
            }
            foreach($ord->users as $user) {
                if($user->user->id == $id) {
                    $isUsers = true;
                    break;
                }
            }
            if($projectCount > 0 && $isUsers) {
                $returnArray[$orderCnt]['name'] = $ord->job_id;
                $returnArray[$orderCnt]['projects'] = $returnProjects;

                $orderCnt++;
            }
        }

        return json_encode($returnArray);
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