<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentRoutingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_document_routings', function (Blueprint $table) {
            $table->increments('dr_id');
            $table->bigInteger('d_id')->unsigned();
            $table->foreign('d_id')->references('d_id')->on('t_documents')->onDelete('cascade');
            $table->integer('u_id')->unsigned();
            $table->foreign('u_id')->references('u_id')->on('t_users')->onDelete('cascade');
            $table->tinyInteger('dr_seen')->default(0);
            $table->tinyInteger('dr_completed')->default(0);
            $table->date('dr_date')->nullable();
            $table->tinyInteger('dr_status')->default(1);
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
        Schema::dropIfExists('t_document_routings');
    }
}