<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accountings', function (Blueprint $table) {
            $table->id('accounting_id');


            $table->unsignedBigInteger('financial_year_id');
            $table->foreign('financial_year_id')
                ->references('financial_year_id')
                ->on('financial_years')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->date('date_transaction')->nullable();
            $table->string('doc_type', 30)->nullable();

            $table->unsignedBigInteger('transaction_type_id');
            $table->foreign('transaction_type_id')
                ->references('transaction_type_id')
                ->on('transaction_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('transaction_no', 30)->nullable();
            $table->string('training_control_no', 30)->nullable();
            
            $table->string('pr_no')->nullable();
            $table->unsignedBigInteger('payee_id')->default(0);

            $table->string('particulars')->nullable();
            $table->string('total_amount')->nullable();
       
            $table->string('pr_status')->nullable();
            $table->string('others')->nullable();
         
            $table->unsignedBigInteger('office_id')->default(0);

            $table->unsignedBigInteger('processor_id')->nullable()
                ->default(0);
            $table->dateTime('processor_datetime_received')->nullable();

            $table->dateTime('bids_award_datetime_forwarded')->nullable();
            $table->dateTime('bids_award_datetime_retrieved')->nullable();
            $table->string('bids_award_status', 30)->nullable();
            $table->string('bids_award_remarks', 255)->nullable();

            $table->dateTime('city_budget_datetime_forwarded')->nullable();
            $table->dateTime('city_budget_datetime_retrieved')->nullable();
            $table->string('city_budget_status', 30)->nullable();
            $table->string('city_budget_remarks', 255)->nullable();

            $table->dateTime('city_accounting_datetime_forwarded')->nullable();
            $table->dateTime('city_accounting_datetime_retrieved')->nullable();
            $table->string('city_accounting_status', 30)->nullable();
            $table->string('city_accounting_remarks', 255)->nullable();

            $table->dateTime('city_treasurer_datetime_forwarded')->nullable();
            $table->dateTime('city_treasurer_datetime_retrieved')->nullable();
            $table->string('city_treasurer_status', 30)->nullable();
            $table->string('city_treasurer_remarks', 255)->nullable();
       
            $table->dateTime('college_accounting_datetime_updated')->nullable();
            $table->string('final_status', 30)->nullable();
            $table->string('final_remarks', 255)->nullable();

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
        Schema::dropIfExists('accountings');
    }
}
