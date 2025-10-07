<?php
// database/seeders/EmployeeSeeder.php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $employees = [];
        $faker = \Faker\Factory::create('id_ID');
        $positions = ['Sales Staff', 'Senior Sales', 'Sales Manager', 'Cashier', 'Store Manager'];

        for ($i = 0; $i < 50; $i++) {
            $employees[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'position' => $faker->randomElement($positions),
                'salary' => $faker->numberBetween(3000000, 15000000),
                'hire_date' => $faker->dateTimeBetween('-5 years', 'now'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Employee::insert($employees);
    }
}