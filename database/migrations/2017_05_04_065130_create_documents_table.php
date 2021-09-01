<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_documents', function (Blueprint $table) {
            $table->bigIncrements('d_id');
            $table->string('d_status', 15);
            $table->string('d_subject');
            $table->integer('dt_id')->unsigned();
            $table->foreign('dt_id')->references('dt_id')->on('t_document_types');
            $table->date('d_documentdate');
            $table->date('d_datereceived')->nullable();
            $table->date('d_datesent')->nullable();
            $table->string('d_sender')->nullable();
            $table->string('d_addressee')->nullable();
            $table->integer('c_id')->unsigned();
            $table->foreign('c_id')->references('c_id')->on('t_companies');            
            $table->text('d_keywords')->nullable();
            $table->text('d_remarks')->nullable();
            $table->string('d_routingslip', 150);
            $table->string('d_incomingreference', 150)->nullable();
            $table->integer('d_routingthru')->default(0);
            $table->integer('d_routingfrom')->default(0);
            $table->string('d_actions')->nullable();
            $table->datetime('d_datetimerouted')->nullable();
            $table->tinyInteger('d_istrack')->default(0);
            $table->tinyInteger('d_iscompleted')->default(0);
            $table->date('d_datecompleted')->nullable();
            $table->integer('d_encoded_by')->nullable();
            $table->integer('d_group_encoded')->nullable();
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
        Schema::dropIfExists('t_documents');
    }
}
