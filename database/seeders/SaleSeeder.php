<?php
// database/seeders/SaleSeeder.php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::pluck('id')->toArray();
        $employees = Employee::pluck('id')->toArray();
        $products = Product::all();

        $sales = [];
        $saleItems = [];

        $faker = \Faker\Factory::create('id_ID');

        for ($saleId = 1; $saleId <= 5000; $saleId++) {
            $saleDate = $faker->dateTimeBetween('-2 years', 'now');
            $customerId = $faker->randomElement($customers);
            $employeeId = $faker->randomElement($employees);
            
            $sales[] = [
                'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($saleId, 6, '0', STR_PAD_LEFT),
                'customer_id' => $customerId,
                'employee_id' => $employeeId,
                'sale_date' => $saleDate,
                'total_amount' => 0, // Akan diupdate nanti
                'status' => 'completed',
                'notes' => $faker->optional(0.3)->sentence,
                'created_at' => $saleDate,
                'updated_at' => $saleDate,
            ];

            // Insert sales dalam batch
            if ($saleId % 500 === 0) {
                Sale::insert($sales);
                $sales = [];
            }
        }

        // Insert sisa sales
        if (!empty($sales)) {
            Sale::insert($sales);
        }

        // Sekarang buat sale items untuk semua sales
        $allSales = Sale::all();
        $productArray = $products->toArray();

        foreach ($allSales as $sale) {
            $numberOfItems = $faker->numberBetween(1, 8);
            $saleTotal = 0;

            for ($j = 0; $j < $numberOfItems; $j++) {
                $product = $faker->randomElement($productArray);
                $quantity = $faker->numberBetween(1, 5);
                $unitPrice = $product['price'];
                $subtotal = $quantity * $unitPrice;
                $saleTotal += $subtotal;

                $saleItems[] = [
                    'sale_id' => $sale->id,
                    'product_id' => $product['id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'created_at' => $sale->sale_date,
                    'updated_at' => $sale->sale_date,
                ];

                // Insert sale items dalam batch besar
                if (count($saleItems) >= 1000) {
                    SaleItem::insert($saleItems);
                    $saleItems = [];
                }
            }

            // Update total amount untuk sale
            DB::table('sales')
                ->where('id', $sale->id)
                ->update(['total_amount' => $saleTotal]);
        }

        // Insert sisa sale items
        if (!empty($saleItems)) {
            SaleItem::insert($saleItems);
        }
    }
}