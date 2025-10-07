<?php
// database/seeders/CustomerSeeder.php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = [];
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 0; $i < 1000; $i++) {
            $customers[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'gender' => $faker->randomElement(['Laki-laki', 'Wanita']),
                'address' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert dalam batch untuk optimasi
            if (($i + 1) % 100 === 0) {
                Customer::insert($customers);
                $customers = [];
            }
        }

        // Insert sisa data
        if (!empty($customers)) {
            Customer::insert($customers);
        }
    }
}