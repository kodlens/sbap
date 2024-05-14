<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
                'username' => 'admin',
                'lname' => 'LADA',
                'fname' => 'CHERRY MAE',
                'mname' => '',
                'suffix' => '',
                'sex' => 'FEMALE',
                'role' => 'ADMINISTRATOR',
                'password' => Hash::make('a')
            ],

            [
                'username' => 'arnel',
                'lname' => 'SELATONA',
                'fname' => 'ARNEL',
                'mname' => '',
                'suffix' => '',
                'sex' => 'MALE',
                'role' => 'PROCESSOR',
                'password' => Hash::make('a')
            ],

            [
                'username' => 'roger',
                'lname' => 'PANDAY',
                'fname' => 'ROGER',
                'mname' => '',
                'suffix' => '',
                'sex' => 'MALE',
                'role' => 'PROCESSOR',
                'password' => Hash::make('a')
            ],

            [
                'username' => 'celso',
                'lname' => 'COLLANTES',
                'fname' => 'CELSO',
                'mname' => '',
                'suffix' => 'JR',
                'sex' => 'MALE',
                'role' => 'PROCESSOR',
                'password' => Hash::make('a')
            ],

            [
                'username' => 'vicente',
                'lname' => 'BACUS.',
                'fname' => 'VICENTE',
                'mname' => '',
                'suffix' => 'JR',
                'sex' => 'MALE',
                'role' => 'BUDGET OFFICER',
                'password' => Hash::make('a')
            ],

            [
                'username' => 'lada',
                'lname' => 'LADA.',
                'fname' => 'CHERRY',
                'mname' => '',
                'suffix' => '',
                'sex' => 'FEMALE',
                'role' => 'PROCUREMENT OFFICER',
                'password' => Hash::make('a')
            ],



        ];

        \App\Models\User::insertOrIgnore($data);
    }
}
