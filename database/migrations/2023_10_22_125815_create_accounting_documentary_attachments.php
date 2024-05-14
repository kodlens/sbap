<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingDocumentaryAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_documentary_attachments', function (Blueprint $table) {
            $table->id('accounting_doc_attachment_id');

            $table->unsignedBigInteger('accounting_id');
            $table->foreign('accounting_id')->references('accounting_id')->on('accountings')
                    ->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('documentary_attachment_id');

            $table->string('documentary_attachment')->nullable();
            $table->string('doc_attachment')->nullable();


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
        Schema::dropIfExists('accounting_documentary_attachments');
    }
}
