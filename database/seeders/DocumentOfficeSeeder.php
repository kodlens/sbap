<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DocumentOfficeSeeder extends Seeder
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
                'sequence_no' => 1,
                'doc_office' => 'Bids & Committee',
            ],
            [
                'sequence_no' => 2,
                'doc_office' => 'City Budget',
            ],
            [
                'sequence_no' => 3,
                'doc_office' => 'City Accounting Office',
            ],
            [
                'sequence_no' => 4,
                'doc_office' => 'City Treasurer\'s Office',
            ],
            [
                'sequence_no' => 5,
                'doc_office' => 'City Accounting Office Office (Reporting Purposes)',
            ],
        
        ];

        \App\Models\DocumentOffice::insertOrIgnore($data);
    }
}
