<?php
// app/Models/Sale.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'employee_id', 
        'sale_date', 
        'total_amount', 
        'status', 
        'notes'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    // Relationship dengan customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship dengan employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Relationship dengan sale items
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Helper method untuk jumlah items dalam sale
    public function getItemsCountAttribute()
    {
        return $this->items()->sum('quantity');
    }

    // Scope untuk sales dalam rentang tanggal
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sale_date', [$startDate, $endDate]);
    }

    // Scope untuk sales bulan ini
    public function scopeThisMonth($query)
    {
        return $query->whereYear('sale_date', now()->year)
                    ->whereMonth('sale_date', now()->month);
    }
}