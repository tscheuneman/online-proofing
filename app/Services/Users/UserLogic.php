<?php

namespace App\Services\Users;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\User;

use App\Mail\UserCreated;
use App\Mail\UserCreatedCAS;

use File;

class UserLogic {

    protected $user;

    /**
     * UserLogic Constructor
     *
     * @param \App\User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get all normal Users
     *
     * @return \App\User[]
     */
    public static function getAll() {
        $users = User::where('org', '!=', 'Admin')->get();
        return $users;
    }

    /**
     * Get a count of all normal Users
     *
     * @return int
     */
    public static function count() {
        return User::where('deleted_at', '=', null)->where('org', '!=', 'Admin')->count();
    }

    /**
     * See if user is active
     *
     * @return boolean
     */
    public function returnActive() {
        return $this->user->active;
    }

    /**
     * Get the user ID
     *
     * @return string
     */
    public function returnID() {
        return $this->user->id;
    }

    /**
     * Get the user
     *
     * @return \App\User
     */
    public function user() {
        return $this->user;
    }

    /**
     * Save user image path,  If exists, remove it first
     *
     * @param string $path
     * @return boolean
     */
    public function saveFile($path) {
        try {
            if($this->user->picture != null) {
                File::delete(public_path(). '/storage/' . $this->user->picture);
            }
            $this->user->picture = $path;
            $this->user->save();
            return true;
        } catch(\Exception $e) {

        }
        return false;
    }

    /**
     * Get user from CAS entry
     *
     * @param string $mail
     * @return mixed
     */
    public static function checkUserCAS($mail) {
        $user = User::where('email', $mail)->first();

        if($user == null) {
            return false;
        }

        return new UserLogic($user);
    }

    /**
     * Find user
     *
     * @return mixed
     */
    public static function findUser($id) {
        $user = User::findOrFail($id);

        return new UserLogic($user);
    }

    /**
     * Create User.  Check for CAS value, send email about account creation
     *
     * @param \Illuminate\Http\Request $request
     * @param string $dept
     * @return string
     */
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

    /**
     * Save Password, Hash
     *
     * @param string $password
     * @return void
     */
    public function savePassword($password) {
        $this->user->password = Hash::make($password);
        $this->user->active = true;
        $this->user->save();
    }
}

?>