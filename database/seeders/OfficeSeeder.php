<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
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
                'office' => 'ICJE',
                'description' => 'INSTITUTE OF CRIMINAL JUSTINCE EDUCATION'
            ],

            [
                'office' => 'IBFS',
                'description' => 'INSTITUTE OF BUSINESS FINANCE AND SERVICES'

            ],
            [
                'office' => 'ICS',
                'description' => 'INSTITUTE OF COMPUTER STUDIES'

            ],
            [
                'office' => 'ACCOUNTING',
                'description' => ''

            ],
            [
                'office' => 'CISO',
                'description' => ''

            ],
            [
                'office' => 'REGISTRAR',
                'description' => ''

            ],
            [
                'office' => 'OSA',
                'description' => 'OFFICE OF STUDENT AFFAIRS'

            ],
            [
                'office' => 'GUIDANCE',
                'description' => ''

            ],

        ];

        \App\Models\Office::insertOrIgnore($data);
    }
}
