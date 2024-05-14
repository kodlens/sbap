<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingExpendituresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_expenditures', function (Blueprint $table) {
            $table->id('accounting_expenditure_id');

            $table->unsignedBigInteger('accounting_id');
            $table->foreign('accounting_id')
                ->references('accounting_id')
                ->on('accountings')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('object_expenditure_id');
            $table->foreign('object_expenditure_id')
                ->references('object_expenditure_id')
                ->on('object_expenditures')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('financial_year_id');

            $table->string('allotment_class_code')->nullable();
            $table->string('allotment_class')->nullable();
            $table->unsignedBigInteger('amount');
            

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
        Schema::dropIfExists('accounting_expenditures');
    }
}
