<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
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
                'transaction_type' => 'PAYROLL'
            ],
            [
                'transaction_type' => 'VOUCHER'
            ],
            [
                'transaction_type' => 'LIQUIDATION REPORT'
            ],
            [
                'transaction_type' => 'OTHERS'
            ],
        ];

        \App\Models\TransactionType::insertOrIgnore($data);
    }
}
