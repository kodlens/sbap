<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectExpendituresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('object_expenditures', function (Blueprint $table) {
            $table->id('object_expenditure_id');
            $table->unsignedBigInteger('financial_year_id');
            $table->foreign('financial_year_id')
                ->references('financial_year_id')
                ->on('financial_years')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('object_expenditure', 100)->nullable();

            $table->string('allotment_class', 100)->nullable();
            $table->string('allotment_class_code', 100)->nullable();

            $table->double('approved_budget')->default(0);
            $table->double('beginning_budget')->default(0);
            $table->double('end_budget')->nullable()->default(0);
            $table->double('utilize_budget')->nullable()->default(0);

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
        Schema::dropIfExists('object_expenditures');
    }
}
