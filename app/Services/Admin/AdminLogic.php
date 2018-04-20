<?php

namespace App\Services\Admin;

use App\Admin;

use App\Services\Users\UserLogic;


class AdminLogic {
    protected $admin;

    /**
     * AdminLogic Constructor
     *
     * @param  \App\Admin $admin
     * @return void
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    /**
     * Find the specified admin
     *
     * @param  string $project
     * @return \App\Services\Admin\AdminLogic
     */
    public static function find($id) {
        $admin = Admin::find($id);

        return new AdminLogic($admin);
    }

    /**
     * Get all admins
     *
     * @return \App\Admin[] $admins
     */

    public static function getAll() {
        $admins = Admin::with('user')->get();
        return $admins;
    }

    /**
     * Return the current admin
     *
     * @return \App\Admin
     */
    public function get() {
        return $this->admin;
    }

    /**
     * Find the admin from a user id
     *
     * @param  string $id
     * @return \App\Services\Admin\AdminLogic
     */
    public static function findAdmin($id) {

        if($admin = Admin::where('user_id', '=', $id)->first()) {
            return new AdminLogic($admin);
        }
        return false;
    }

    /**
     * See if the admin is active
     *
     * @return boolean
     */
    public function isActive() {
        return $this->admin->active;
    }

    /**
     * Get an AdminLogic object from a given UserLogic
     *
     * @param  \App\Services\Users\UserLogic $user
     * @return \App\Services\Admin\AdminLogic
     */
    public static function findFromUser(UserLogic $user) {
        $admin = Admin::where('user_id', '=', $user->returnID())->first();
        return new AdminLogic($admin);
    }

    /**
     * Create an admin from a userID
     *
     * @param  string $user
     * @return \App\Services\Admin\AdminLogic
     */
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

    /**
     * Set admin as active
     *
     * @return void
     */
    public function makeActive() {
        $this->admin->active = true;
        $this->admin->save();
    }
}

?>