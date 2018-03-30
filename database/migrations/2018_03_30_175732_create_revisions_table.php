<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->primary('id');

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('admins');

            $table->uuid('project_id');
            $table->foreign('project_id')->references('id')->on('projects');

            $table->uuid('entry_id');
            $table->foreign('entry_id')->references('id')->on('entries');

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
        Schema::dropIfExists('revisions');
    }
}
