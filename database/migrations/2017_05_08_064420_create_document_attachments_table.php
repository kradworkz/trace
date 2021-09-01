<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_document_attachments', function (Blueprint $table) {
            $table->increments('da_id');
            $table->bigInteger('d_id')->unsigned();
            $table->foreign('d_id')->references('d_id')->on('t_documents')->onDelete('cascade');
            $table->text('da_file')->nullable();
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
        Schema::dropIfExists('t_document_attachments');
    }
}
