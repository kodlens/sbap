<?php

namespace Database\Seeders;

use App\Models\AllotmentClass;
use App\Models\AppointmentType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            FinancialYearSeeder::class,
            TransactionTypeSeeder::class,
            OfficeSeeder::class,
            DocumentaryAttachmentSeeder::class,
            PayeeSeeder::class,
            AllotmentClassSeeder::class,
            ObjectExpenditureSeeder::class
        ]);
    }
}
