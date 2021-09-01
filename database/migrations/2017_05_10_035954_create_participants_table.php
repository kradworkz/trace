<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_participants', function (Blueprint $table) {
            $table->increments('p_id');
            $table->integer('m_id')->unsigned();
            $table->foreign('m_id')->references('m_id')->on('t_meetings')->onDelete('cascade');
            $table->integer('u_id')->unsigned();
            $table->foreign('u_id')->references('u_id')->on('t_users')->onDelete('cascade');
            $table->tinyInteger('p_ord')->default(0);
            $table->tinyInteger('p_seen')->default(0);
            $table->string('p_notif')->default('Pending');
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
        Schema::dropIfExists('t_participants');
    }
}
