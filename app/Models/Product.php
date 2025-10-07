<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description', 
        'price', 
        'stock', 
        'category_id'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    // Relationship dengan category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship dengan sale items
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Alias untuk kompatibilitas dengan query sebelumnya
    public function sales()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Helper method untuk total terjual
    public function getTotalSoldAttribute()
    {
        return $this->saleItems()->sum('quantity');
    }

    // Helper method untuk total revenue
    public function getTotalRevenueAttribute()
    {
        return $this->saleItems()->sum('subtotal');
    }

    // Scope untuk produk yang habis
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    // Scope untuk produk dengan stok rendah
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '<=', $threshold);
    }
}