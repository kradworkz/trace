<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_zoom_settings', function (Blueprint $table) {
            $table->increments('zs_id');
            $table->string('zs_email', 255)->nullable();
            $table->string('zs_password', 255)->nullable();
            $table->string('zs_remarks', 255)->nullable();
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
        Schema::dropIfExists('t_zoom_settings');
    }
}
