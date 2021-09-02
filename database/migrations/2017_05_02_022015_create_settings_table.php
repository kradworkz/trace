<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_settings', function(Blueprint $table) {
            $table->increments('s_id');
            $table->string('s_sysname');
            $table->string('s_abbr');
            $table->integer('s_pending_days');                
            $table->string('s_background', 250);
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
        Schema::dropIfExists('t_settings');
    }
}
