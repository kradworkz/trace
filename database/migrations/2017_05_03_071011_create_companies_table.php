<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_companies', function (Blueprint $table) {
            $table->increments('c_id');
            $table->string('c_name');
            $table->string('c_address')->nullable();
            $table->string('c_acronym')->nullable();
            $table->string('c_telephone')->nullable();
            $table->string('c_fax')->nullable();
            $table->string('c_email')->nullable();
            $table->string('c_website')->nullable();
            $table->integer('u_id');            
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
        Schema::dropIfExists('t_companies');
    }
}
