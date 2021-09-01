<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_users', function (Blueprint $table) {
            $table->increments('u_id');
            $table->string('u_username');
            $table->string('u_email');
            $table->string('u_password');
            $table->string('u_fname');
            $table->string('u_mname');
            $table->string('u_lname');
            $table->string('u_mobile');
            $table->integer('ug_id')->unsigned()->default(4);
            $table->foreign('ug_id')->references('ug_id')->on('t_user_groups');
            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('group_id')->on('t_groups');
            $table->string('u_position');
            $table->string('u_picture')->default('upload/profile/no-user-photo.png');
            $table->tinyInteger('u_active')->default(0);
            $table->tinyInteger('u_administrator')->default(0);
            $table->tinyInteger('u_head')->default(0);
            $table->tinyInteger('u_zoom_mgr')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('t_users');
    }
}
