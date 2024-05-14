<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PayeeSeeder extends Seeder
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
                'bank_account_payee' => 'PAYEE 01',
                'owner' => 'OWNER 01',
                'tin' => '123-123-111',
                'contact_no' => '09161112222',
                'email' => 'test01@gmail.com'
            ],
            [
                'bank_account_payee' => 'PAYEE 02',
                'owner' => 'OWNER 02',
                'tin' => '123-123-2222',
                'contact_no' => '09161113333',
                'email' => 'test02@gmail.com'
            ],
            [
                'bank_account_payee' => 'PAYEE 03',
                'owner' => 'OWNER 03',
                'tin' => '123-123-3333',
                'contact_no' => '09161113334',
                'email' => 'test03@gmail.com'
            ],

            [
                'bank_account_payee' => 'PAYEE 04',
                'owner' => 'OWNER 04',
                'tin' => '123-123-4444',
                'contact_no' => '09161113335',
                'email' => 'test04@gmail.com'
            ],



        ];

        \App\Models\Payee::insertOrIgnore($data);
    }
}
