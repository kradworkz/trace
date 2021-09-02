<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_comments', function (Blueprint $table) {
            $table->increments('comm_id');
            $table->tinyInteger('comm_document')->default(0);
            $table->tinyInteger('comm_event')->default(0);
            $table->integer('comm_reference');
            $table->integer('u_id')->unsigned();
            $table->foreign('u_id')->references('u_id')->on('t_users');
            $table->text('comm_text')->nullable();            
            $table->tinyInteger('comm_rd')->default(0);
            $table->tinyInteger('comm_for_rd')->default(0);
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
        Schema::dropIfExists('t_comments');
    }
}
