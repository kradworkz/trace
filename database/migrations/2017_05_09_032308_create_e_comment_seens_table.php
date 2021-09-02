<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateECommentSeensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ecomment_seen', function (Blueprint $table) {
            $table->increments('ecs_id');
            $table->integer('e_id')->unsigned();
            $table->foreign('e_id')->references('e_id')->on('t_events')->onDelete('cascade');
            $table->integer('comm_id')->unsigned();
            $table->foreign('comm_id')->references('comm_id')->on('t_comments')->onDelete('cascade');
            $table->integer('u_id')->unsigned();
            $table->foreign('u_id')->references('u_id')->on('t_users')->onDelete('cascade');
            $table->tinyInteger('ecs_seen')->default(0);
            $table->timestamps();
        });;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_ecomment_seen');
    }
}
