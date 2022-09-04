<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Admin;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminCreated;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pwReturn = 'password';

        $user = new User();
            $user->first_name = "FirstName";
            $user->last_name = "LastName";
            $user->org = "Admin";
            $user->email = ENV('DEFAULT_ADMIN');
            $user->name = ENV('DEFAULT_ADMIN');
            $user->password = Hash::make($pwReturn);
            $user->active = true;
            $user->save();

        $admin = new Admin();
            $admin->user_id = $user->id;
            $admin->save();

        Mail::to($user->email)->send(new AdminCreated($user, $pwReturn));
    }
}
