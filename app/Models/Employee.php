<?php
// app/Models/Employee.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email', 
        'phone', 
        'position', 
        'salary', 
        'hire_date'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Helper method untuk total penjualan
    public function getTotalSalesAttribute()
    {
        return $this->sales()->sum('total_amount');
    }

    // Helper method untuk jumlah transaksi
    public function getSalesCountAttribute()
    {
        return $this->sales()->count();
    }
}