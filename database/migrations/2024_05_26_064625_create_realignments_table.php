<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realignments', function (Blueprint $table) {
            $table->id('realignment_id');

            $table->unsignedBigInteger('financial_year_id');
            $table->foreign('financial_year_id')
                ->references('financial_year_id')
                ->on('financial_years')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('remarks')->nullable();

            $table->unsignedBigInteger('object_expenditure_id_from');
            $table->foreign('object_expenditure_id_from')
                ->references('object_expenditure_id')
                ->on('object_expenditures')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('object_expenditure_id_to');
            $table->foreign('object_expenditure_id_to')
                ->references('object_expenditure_id')
                ->on('object_expenditures')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->double('amount_transfer')
                ->default(0);


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
        Schema::dropIfExists('realignments');
    }
}
