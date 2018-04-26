<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->primary('id');

            $table->uuid('thread_id');
            $table->foreign('thread_id')->references('id')->on('message_threads')->delete('cascade');

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->delete('cascade');

            $table->text('message');

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
        Schema::dropIfExists('messages');
    }
}
