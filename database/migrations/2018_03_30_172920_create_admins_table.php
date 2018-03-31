<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Admin;
class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->primary('id');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('picture')->nullable(true)->default(null);
            $table->dateTime('deleted_at')->nullable(true)->default(null);
            $table->boolean('active')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        $admin = new Admin();
            $admin->first_name = 'Thomas';
            $admin->last_name = "Scheuneman";
            $admin->email = "thomas.scheuneman@asu.edu";
            $admin->password = "testing";
            $admin->active = true;
            $admin->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
