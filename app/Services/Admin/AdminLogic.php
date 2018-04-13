<?php

namespace App\Services\Admin;

use App\Admin;

use App\Services\Users\UserLogic;


class AdminLogic {
    protected $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    public static function getAll() {
        $admins = Admin::with('user')->get();
        return $admins;
    }

    public static function findAdmin($id) {
        $admin = Admin::where('user_id', '=', $id)->first();

        return new AdminLogic($admin);
    }

    public function isActive() {
        return $this->admin->active;
    }

    public static function findFromUser(UserLogic $user) {
        $admin = Admin::where('user_id', '=', $user->returnID())->first();
        return new AdminLogic($admin);
    }

    public static function createAdmin($user) {
        try {
            $admin = new Admin();
            $admin->user_id = $user;
            $admin->save();

            return new AdminLogic($admin);

        } catch(\Exception $e) {
            return false;
        }
    }

    public function makeActive() {
        $this->admin->active = true;
        $this->admin->save();
    }
}

?>