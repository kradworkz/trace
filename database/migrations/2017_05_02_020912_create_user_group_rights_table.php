<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupRightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ug_rights', function (Blueprint $table) {
            $table->increments('ugr_id');
            $table->integer('ug_id')->unsigned();
            $table->foreign('ug_id')->references('ug_id')->on('t_user_groups');
            $table->integer('ur_id')->unsigned();
            $table->foreign('ur_id')->references('ur_id')->on('t_user_rights');
            $table->tinyInteger('ugr_view');
            $table->tinyInteger('ugr_add');
            $table->tinyInteger('ugr_edit');
            $table->tinyInteger('ugr_delete');            
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
        Schema::dropIfExists('t_ug_rights');
    }
}
