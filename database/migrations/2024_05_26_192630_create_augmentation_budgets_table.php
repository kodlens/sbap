<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAugmentationBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('augmentation_budgets', function (Blueprint $table) {
            $table->id('augmentation_budget_id');

            $table->unsignedBigInteger('financial_year_id');
            $table->foreign('financial_year_id')
                ->references('financial_year_id')
                ->on('financial_years')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('remarks')->nullable();

            $table->unsignedBigInteger('object_expenditure_id');
            $table->foreign('object_expenditure_id')
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
        Schema::dropIfExists('augmentation_budgets');
    }
}
