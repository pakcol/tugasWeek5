<?php
// app/Models/Customer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'gender', 
        'address'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Helper method untuk total spending
    public function getTotalSpentAttribute()
    {
        return $this->sales()->sum('total_amount');
    }

    // Helper method untuk total items dibeli
    public function getTotalItemsPurchasedAttribute()
    {
        return $this->sales()->with('items')->get()->sum(function($sale) {
            return $sale->items->sum('quantity');
        });
    }
}