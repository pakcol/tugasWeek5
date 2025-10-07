<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Elektronik', 'description' => 'Produk elektronik dan gadget'],
            ['name' => 'Pakaian Pria', 'description' => 'Pakaian untuk pria'],
            ['name' => 'Pakaian Wanita', 'description' => 'Pakaian untuk wanita'],
            ['name' => 'Makanan & Minuman', 'description' => 'Makanan dan minuman'],
            ['name' => 'Otomotif', 'description' => 'Sparepart dan aksesori kendaraan'],
            ['name' => 'Rumah Tangga', 'description' => 'Peralatan rumah tangga'],
            ['name' => 'Kesehatan & Kecantikan', 'description' => 'Produk kesehatan dan kecantikan'],
            ['name' => 'Olahraga', 'description' => 'Perlengkapan olahraga'],
            ['name' => 'Buku & Alat Tulis', 'description' => 'Buku dan alat tulis'],
            ['name' => 'Mainan & Hobi', 'description' => 'Mainan dan produk hobi'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}