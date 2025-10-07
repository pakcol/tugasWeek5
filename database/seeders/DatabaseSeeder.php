<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks untuk performa
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Truncate tables
        Customer::truncate();
        Employee::truncate();
        Category::truncate();
        Product::truncate();
        Sale::truncate();
        SaleItem::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->call([
            CategorySeeder::class,
            CustomerSeeder::class,
            EmployeeSeeder::class,
            ProductSeeder::class,
            SaleSeeder::class,
        ]);
    }
}