<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecificationEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specification_entries', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->primary('id');

            $table->uuid('specification_id');
            $table->foreign('specification_id')->references('id')->on('specifications')->delete('cascade');

            $table->uuid('schema_id');
            $table->foreign('schema_id')->references('id')->on('specification_schemas')->delete('cascade');

            $table->string('value')->nullable(false);

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
        Schema::dropIfExists('specification_entries');
    }
}
