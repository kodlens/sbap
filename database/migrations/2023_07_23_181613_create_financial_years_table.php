<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_years', function (Blueprint $table) {
            $table->id('financial_year_id');
            $table->string('financial_year_code')->nullable();
            $table->string('financial_year_desc')->nullable();
            $table->double('approved_budget')->nullable()
                ->default(0);
            $table->double('beginning_budget')->nullable()
                ->default(0);

            $table->double('end_budget')->nullable()->default(0);
            $table->double('utilize_budget')->nullable()->default(0);
    
            $table->tinyInteger('active')->default(0);
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
        Schema::dropIfExists('financial_years');
    }
}
