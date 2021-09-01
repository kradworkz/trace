<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_user_logs', function (Blueprint $table) {
            $table->increments('ul_id');
            $table->integer('u_id')->unsigned();         
            $table->foreign('u_id')->references('u_id')->on('t_users');
            $table->string('ul_ip');
            $table->string('ul_location', 255);
            $table->string('ul_session');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_user_logs');
    }
}