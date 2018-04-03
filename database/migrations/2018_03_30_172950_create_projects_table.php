<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->primary('id');

            $table->string('project_name');
            $table->string('file_path');
            $table->dateTime('deleted_at')->nullable(true)->default(null);

            $table->boolean('hidden')->default(false);

            $table->boolean('notify_users')->default(false);
            $table->boolean('notify_admins')->default(false);

            $table->boolean('active')->default(true);

            $table->uuid('cat_id');
            $table->foreign('cat_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('projects');
    }
}
