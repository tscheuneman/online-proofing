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
        $pwReturn = str_random(12);

        $user = new User();
            $user->first_name = "Thomas";
            $user->last_name = "Scheuneman";
            $user->org = "Admin";
            $user->email = "thomas@tswebvisions.com";
            $user->name = "thomas@tswebvisions.com";
            $user->password = Hash::make($pwReturn);
            $user->active = true;
            $user->save();

        $admin = new Admin();
            $admin->user_id = $user->id;
            $admin->save();

        Mail::to($user->email)->send(new AdminCreated($user, $pwReturn));
    }
}
