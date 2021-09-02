<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventSeensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_event_seen', function (Blueprint $table) {
            $table->increments('es_id');
            $table->integer('e_id')->unsigned();
            $table->foreign('e_id')->references('e_id')->on('t_events')->onDelete('cascade');
            $table->integer('u_id')->unsigned();
            $table->foreign('u_id')->references('u_id')->on('t_users')->onDelete('cascade');
            $table->tinyInteger('es_seen')->default(0);
            $table->tinyInteger('es_invited')->default(0);
            $table->tinyInteger('e_confirm')->default(0);
            $table->tinyInteger('es_confirmed')->default(99);
            $table->text('es_reason')->nullable();
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
        Schema::dropIfExists('t_event_seen');
    }
}