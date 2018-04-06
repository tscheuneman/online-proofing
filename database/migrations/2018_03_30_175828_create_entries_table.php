<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->primary('id');

            $table->uuid('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->delete('cascade');

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('path')->unique();
            $table->string('pdf_name')->nullable(true);

            $table->json('files')->nullable(true);
            $table->boolean('admin')->default(false);

            $table->boolean('active')->default(false);

            $table->text('notes');
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
        Schema::dropIfExists('entries');
    }
}
