<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->primary('id');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('org')->nullable(false);
            $table->string('picture')->nullable(true)->default(null);
            $table->dateTime('deleted_at')->nullable(true)->default(null);
            $table->boolean('active')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        $user = new User();
            $user->first_name = 'Thomas';
            $user->last_name = "Scheuneman";
            $user->org = "PIL";
            $user->email = "thomas.scheuneman@asu.edu";
            $user->password = "testing";
            $user->active = true;
            $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
