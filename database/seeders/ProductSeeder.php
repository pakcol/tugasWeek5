<?php
// database/seeders/ProductSeeder.php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [];
        $faker = \Faker\Factory::create('id_ID');
        
        $productNames = [
            // Elektronik
            'Smartphone', 'Laptop', 'Tablet', 'Smart TV', 'Headphone', 'Speaker', 'Kamera', 'Drone',
            'Smart Watch', 'Power Bank', 'Router', 'Mouse', 'Keyboard', 'Monitor', 'Printer',
            
            // Pakaian
            'Kemeja', 'Celana Jeans', 'Jaket', 'Sweater', 'Kaos', 'Rok', 'Dress', 'Blouse',
            'Sepatu Sneakers', 'Sepatu Formal', 'Tas', 'Topi', 'Dompet', 'Belt',
            
            // Makanan
            'Snack Box', 'Minuman Energi', 'Kopi Premium', 'Teh Botol', 'Biscuit', 'Coklat',
            'Mie Instan', 'Sarden Kaleng', 'Susu UHT', 'Jus Buah',
            
            // Otomotif
            'Oli Mobil', 'Ban Mobil', 'Aki Mobil', 'Spion', 'Lampu Mobil', 'Wiper',
            'Velg Racing', 'Kampas Rem', 'Filter Udara', 'Antena',
            
            // Rumah Tangga
            'Blender', 'Microwave', 'Kipas Angin', 'Rice Cooker', 'Panci', 'Wajan',
            'Piring', 'Gelas', 'Sendok', 'Garpu', 'Pisau Dapur', 'Talenan',
        ];

        for ($i = 0; $i < 500; $i++) {
            $productName = $faker->randomElement($productNames) . ' ' . $faker->word . ' ' . $faker->randomNumber(3);
            
            $products[] = [
                'name' => $productName,
                'description' => $faker->sentence(10),
                'price' => $faker->numberBetween(10000, 10000000),
                'stock' => $faker->numberBetween(0, 500),
                'category_id' => $faker->numberBetween(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert dalam batch
            if (($i + 1) % 100 === 0) {
                Product::insert($products);
                $products = [];
            }
        }

        if (!empty($products)) {
            Product::insert($products);
        }
    }
}