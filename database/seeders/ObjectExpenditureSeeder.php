<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ObjectExpenditureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $data = [
            [
                'financial_year_id' => 2,
                'object_expenditure' => 'Traveling Expenses',
                'allotment_class' => 'GENERAL SERVICES',
                'allotment_class_code' => 'JOS',
                'approved_budget' => '1800000',
                'beginning_budget' => '1800000'
            ],
            [
                'financial_year_id' => 2,
                'object_expenditure' => 'Internet',
                'allotment_class' => 'MAINTENANCE AND OTHER OPERATING EXPENSE',
                'allotment_class_code' => 'MOOE',
                'approved_budget' => '1400000',
                'beginning_budget' => '1400000'
            ],
            
         
       

        ];

        \App\Models\ObjectExpenditure::insertOrIgnore($data);

    }
}
