<?php

namespace App\Services\Users;

use App\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Mail\UserCreated;

class UserLogic {
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function getAll() {
        $users = User::where('org', '!=', 'Admin')->get();
        return $users;
    }

    public function returnID() {
        return $this->user->id;
    }

    public static function findUser($id) {
        $user = User::find($id);

        return new UserLogic($user);
    }

    public static function createUser($request, $dept) {
        $pwReturn = str_random(12);

        $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->org = $dept;
            $user->email = $request->email;
            $user->name = $request->email;
            $user->password = Hash::make($pwReturn);
            $user->active = false;
            $user->save();

        Mail::to($request->email)->send(new UserCreated($user, $pwReturn));

        return $user->id;
    }

    public function savePassword($password) {
        $this->user->password = Hash::make($password);
        $this->user->active = true;
        $this->user->save();
    }
}

?>