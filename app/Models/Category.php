<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Helper method untuk total produk terjual per kategori
    public function getTotalSoldAttribute()
    {
        return $this->products()->with('saleItems')->get()->sum(function($product) {
            return $product->saleItems->sum('quantity');
        });
    }

    // Helper method untuk revenue per kategori
    public function getTotalRevenueAttribute()
    {
        return $this->products()->with('saleItems')->get()->sum(function($product) {
            return $product->saleItems->sum('subtotal');
        });
    }
}