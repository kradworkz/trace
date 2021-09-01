<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_meetings', function (Blueprint $table) {
            $table->increments('m_id');
            $table->date('m_startdate')->nullable();
            $table->date('m_enddate')->nullable();
            $table->string('m_starttime', 15);
            $table->string('m_endtime', 15);
            $table->date('m_tstartdate')->nullable();
            $table->date('m_tenddate')->nullable();
            $table->string('m_tstarttime', 15)->nullable();
            $table->string('m_tendtime', 15)->nullable();
            $table->text('m_purpose')->nullable();
            $table->text('m_destination')->nullable();
            $table->text('m_others')->nullable();
            $table->integer('m_encodedby')->unsigned();
            $table->foreign('m_encodedby')->references('u_id')->on('t_users')->onDelete('cascade');
            $table->string('m_status')->default('Pending');
            $table->text('m_reason')->nullable();
            $table->date('m_datechecked')->nullable();
            $table->integer('m_postponedby')->default(0);
            $table->tinyInteger('m_stat')->default(0);
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
        Schema::dropIfExists('t_meetings');
    }
}
