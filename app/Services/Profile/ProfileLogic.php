<?php

namespace App\Services\Profile;

use App\User;


class ProfileLogic {
    protected $user;

    /**
     * ProjectLogic Controller
     *
     * @param \App\Project $project
     * @return void
     */

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * ProjectLogic Controller
     *
     * @param \App\user $user
     * @param String $first_name
     * @param String $last_name
     * @return ProfileLogic
     */
    public static function update(User $user, $first_name, $last_name) {
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->save();

        return new ProfileLogic($user);
    }


}

?>