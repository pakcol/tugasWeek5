<?php
// app/Models/SaleItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id', 
        'quantity', 
        'unit_price', 
        'subtotal'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    // Relationship dengan sale
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Relationship dengan product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Auto calculate subtotal sebelum save
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($saleItem) {
            $saleItem->subtotal = $saleItem->quantity * $saleItem->unit_price;
        });
    }
}