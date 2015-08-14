<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrchestraTransporterCreateMigrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orchestra_transporter', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->unsignedInteger('source_id');
            $table->unsignedInteger('destination_id');

            $table->index(['name', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orchestra_transporter');
    }
}
