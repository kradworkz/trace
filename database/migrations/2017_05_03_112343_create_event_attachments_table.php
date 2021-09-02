<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_event_attachments', function (Blueprint $table) {
            $table->increments('ea_id');
            $table->integer('e_id')->unsigned();
            $table->foreign('e_id')->references('e_id')->on('t_events')->onDelete('cascade');
            $table->string('ea_file')->nullable();
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
        Schema::dropIfExists('t_event_attachments');
    }
}