<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionDonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_action_done', function (Blueprint $table) {
            $table->increments('ad_id');
            $table->integer('comm_id')->unsigned();
            $table->foreign('comm_id')->references('comm_id')->on('t_comments')->onDelete('cascade');
            $table->bigInteger('d_id')->unsigned();
            $table->foreign('d_id')->references('d_id')->on('t_documents')->onDelete('cascade');
            $table->integer('u_id')->unsigned();
            $table->foreign('u_id')->references('u_id')->on('t_users')->onDelete('cascade');
            $table->tinyInteger('ad_seen')->default(0);
            $table->tinyInteger('ad_rd')->default(0);
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
        Schema::dropIfExists('t_action_done');
    }
}
