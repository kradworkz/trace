<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_events', function (Blueprint $table) {
            $table->increments('e_id');
            $table->string('e_name');
            $table->string('e_type');
            $table->string('e_start_date');
            $table->string('e_start_time');
            $table->string('e_end_date');
            $table->string('e_end_time');
            $table->string('e_keywords')->nullable();
            $table->string('e_staff')->default('');
            $table->string('e_venue')->nullable();
            $table->tinyInteger('e_live')->default(0);
            $table->integer('u_id')->unsigned();
            $table->foreign('u_id')->references('u_id')->on('t_users');
            $table->tinyInteger('e_confirm')->default(0);
            $table->tinyInteger('e_online')->default(0);
            $table->tinyInteger('e_zoom')->default(0);
            $table->integer('zs_id')->nullable();
            $table->string('e_zoom_pw', 255)->nullable();
            $table->tinyInteger('e_zoom_approved')->default(0);
            $table->datetime('e_zoom_date')->nullable();
            $table->text('e_zoom_link')->nullable();
            $table->string('e_zoom_mtgid', 255)->nullable();
            $table->text('e_zoom_reason')->nullable();
            $table->tinyInteger('e_zoom_seen')->default(1);
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
        Schema::dropIfExists('t_events');
    }
}