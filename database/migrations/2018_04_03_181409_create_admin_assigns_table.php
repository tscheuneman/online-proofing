<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_assigns', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->primary('id');

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('admins');

            $table->uuid('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->boolean('notify')->default(true);

            $table->dateTime('deleted_at')->nullable(true)->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_assigns');
    }
}
