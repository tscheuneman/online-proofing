<?php

namespace App\Services\Users;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\User;

use App\Mail\UserCreated;
use App\Mail\UserCreatedCAS;

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

    public static function count() {
        return User::where('deleted_at', '=', null)->where('org', '!=', 'Admin')->count();
    }

    public function returnActive() {
        return $this->user->active;
    }

    public function returnID() {
        return $this->user->id;
    }

    public function user() {
        return $this->user;
    }

    public static function checkUserCAS($mail) {
        $user = User::where('email', $mail)->first();

        if($user == null) {
            return false;
        }

        return new UserLogic($user);
    }

    public static function findUser($id) {
        $user = User::find($id);

        return new UserLogic($user);
    }

    public static function createUser($request, $dept) {
        $explodedEmail = explode('@', $request->email);
        $domain = array_pop($explodedEmail);

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


        if($domain == ENV('CAS_APPEND')) {
            Mail::to($request->email)->send(new UserCreatedCAS($user));
            $user->active = true;
            $user->save();
        }
        else {
            Mail::to($request->email)->send(new UserCreated($user, $pwReturn));
        }



        return $user->id;
    }

    public function savePassword($password) {
        $this->user->password = Hash::make($password);
        $this->user->active = true;
        $this->user->save();
    }
}

?>