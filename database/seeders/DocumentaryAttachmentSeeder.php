<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DocumentaryAttachmentSeeder extends Seeder
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
                'documentary_attachment' => 'PAYROLL',
            ],
            [
                'documentary_attachment' => 'VOUCHER',
            ],
            [
                'documentary_attachment' => 'CITY/BOT RESOLUTION',
            ],
            [
                'documentary_attachment' => 'ACCOMPLISHMENT REPORT',
            ],
            [
                'documentary_attachment' => 'DTR',
            ],
            [
                'documentary_attachment' => 'STATEMENT OF ACCOUNT / INVOICE',
            ],
            [
                'documentary_attachment' => 'OFFICIAL RECEIPTS',
            ],
            [
                'documentary_attachment' => 'DELIVERY RECEIPTS',
            ],
            [
                'documentary_attachment' => 'CONTRACT OF SERVICE',
            ],
            [
                'documentary_attachment' => 'TRAINING / ACTIVITY DESIGNS',
            ],
            [
                'documentary_attachment' => 'CV / TOR',
            ],
            [
                'documentary_attachment' => 'CAF',
            ],
            [
                'documentary_attachment' => 'PURCHASE ORDER',
            ],
            [
                'documentary_attachment' => 'PURCHASE REQUEST',
            ],
            [
                'documentary_attachment' => 'ITINERARY OF TRAVEL',
            ],
            [
                'documentary_attachment' => 'TRAVEL ORDER',
            ],
            [
                'documentary_attachment' => 'FACULTY LOAD',
            ],
            [
                'documentary_attachment' => 'CERTIFICATE OF ENROLMENT',
            ],
            [
                'documentary_attachment' => 'CERTIFICATION',
            ],
            [
                'documentary_attachment' => 'JUSTIFICATION',
            ],
            [
                'documentary_attachment' => 'COMMUNICATION / INVITATION LETTER / PROGRAM',
            ],
            [
                'documentary_attachment' => 'OTHERS',
            ],
        ];

        \App\Models\DocumentaryAttachment::insertOrIgnore($data);
    }
}
