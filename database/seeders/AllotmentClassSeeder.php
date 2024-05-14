<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AllotmentClassSeeder extends Seeder
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
                'allotment_class_code' => 'PS',
                'allotment_class' => 'PERSONAL SERVICES',
            ],
            [
                'allotment_class_code' => 'JOs',
                'allotment_class' => 'GENERAL SERVICES',
            ],
            [
                'allotment_class_code' => 'MOOE',
                'allotment_class' => 'MAINTENANCE AND OTHER OPERATING EXPENSE',
            ],
            [
                'allotment_class_code' => 'OTHER',
                'allotment_class' => 'OTHER PRIORITY PROGRAM',
            ],
            
        
        ];

        \App\Models\AllotmentClass::insertOrIgnore($data);

    }
}
