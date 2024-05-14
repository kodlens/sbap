<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_logs', function (Blueprint $table) {
            $table->id('doc_log_id');

            $table->unsignedBigInteger('accounting_id')
                ->nullable()->default(0);

            $table->string('office')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('datetime_forwarded')->nullable();
            $table->dateTime('dateTimeReceived')->nullable();
            $table->string('remarks')->nullable();

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
        Schema::dropIfExists('document_logs');
    }
}
