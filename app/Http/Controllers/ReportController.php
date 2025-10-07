<?php
// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function dashboard()
    {
        try {
            // 1. Top 5 barang paling laku
            $topProducts = Product::select('products.*')
                ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
                ->selectRaw('products.*, SUM(sale_items.quantity) as total_sold')
                ->groupBy('products.id')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();

            // 2. Top 1 kategori paling laku
            $topCategory = Category::select('categories.*')
                ->join('products', 'categories.id', '=', 'products.category_id')
                ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
                ->selectRaw('categories.*, SUM(sale_items.quantity) as total_sold')
                ->groupBy('categories.id')
                ->orderByDesc('total_sold')
                ->first();

            // 3. Top 3 spender
            $topSpenders = Customer::select('customers.*')
                ->join('sales', 'customers.id', '=', 'sales.customer_id')
                ->selectRaw('customers.*, SUM(sales.total_amount) as total_spent')
                ->groupBy('customers.id')
                ->orderByDesc('total_spent')
                ->limit(3)
                ->get();

            // 4. Top buyer
            $topBuyer = Customer::select('customers.*')
                ->join('sales', 'customers.id', '=', 'sales.customer_id')
                ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
                ->selectRaw('customers.*, SUM(sale_items.quantity) as total_items')
                ->groupBy('customers.id')
                ->orderByDesc('total_items')
                ->first();

            // Data statistik tambahan
            $totalCustomers = Customer::count();
            $totalProducts = Product::count();
            $totalSales = Sale::count();
            $totalRevenue = Sale::sum('total_amount');

            return view('welcome', compact(
                'topProducts',
                'topCategory',
                'topSpenders',
                'topBuyer',
                'totalCustomers',
                'totalProducts',
                'totalSales',
                'totalRevenue'
            ));

        } catch (\Exception $e) {
            // Fallback jika ada error
            return view('welcome', [
                'topProducts' => collect(),
                'topCategory' => null,
                'topSpenders' => collect(),
                'topBuyer' => null,
                'totalCustomers' => 0,
                'totalProducts' => 0,
                'totalSales' => 0,
                'totalRevenue' => 0
            ]);
        }
    }
    // 1. Top 5 barang paling laku
    public function top5Products()
    {
        $topProducts = Product::select('products.*')
            ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->selectRaw('products.*, SUM(sale_items.quantity) as total_sold')
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return response()->json($topProducts);
    }

    // 2. Top 1 kategori paling laku
    public function topCategory()
    {
        $topCategory = Category::select('categories.*')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->selectRaw('categories.*, SUM(sale_items.quantity) as total_sold')
            ->groupBy('categories.id')
            ->orderByDesc('total_sold')
            ->first();

        return response()->json($topCategory);
    }

    // 3. Top 3 spender (total nominal pembelian terbanyak)
    public function top3Spenders()
    {
        $topSpenders = Customer::select('customers.*')
            ->join('sales', 'customers.id', '=', 'sales.customer_id')
            ->selectRaw('customers.*, SUM(sales.total_amount) as total_spent')
            ->groupBy('customers.id')
            ->orderByDesc('total_spent')
            ->limit(3)
            ->get();

        return response()->json($topSpenders);
    }

    // 4. Top buyer (total item pembelian terbanyak)
    public function topBuyer()
    {
        $topBuyer = Customer::select('customers.*')
            ->join('sales', 'customers.id', '=', 'sales.customer_id')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->selectRaw('customers.*, SUM(sale_items.quantity) as total_items')
            ->groupBy('customers.id')
            ->orderByDesc('total_items')
            ->first();

        return response()->json($topBuyer);
    }

    // 5. Pembeli dengan total pembelian > rata-rata bulan tersebut
    public function customersAboveMonthlyAverage()
    {
        $customers = Customer::select('customers.*')
            ->join('sales', 'customers.id', '=', 'sales.customer_id')
            ->selectRaw('customers.*, 
                        MONTH(sales.sale_date) as month,
                        YEAR(sales.sale_date) as year,
                        SUM(sales.total_amount) as total_monthly_purchase')
            ->groupBy('customers.id', 'month', 'year')
            ->havingRaw('total_monthly_purchase > (
                SELECT AVG(total_amount) 
                FROM sales 
                WHERE MONTH(sale_date) = month AND YEAR(sale_date) = year
            )')
            ->get();

        return response()->json($customers);
    }

    // 6. Rata-rata total pembelian 3 bulan terakhir
    public function last3MonthsAverage()
    {
        $threeMonthsAgo = Carbon::now()->subMonths(3);

        $average = Sale::where('sale_date', '>=', $threeMonthsAgo)
            ->selectRaw('AVG(total_amount) as average_sales')
            ->first();

        return response()->json($average);
    }

    // 7. Total pembelian terbesar dari pelanggan wanita
    public function topFemaleCustomerPurchase()
    {
        $topPurchase = Customer::where('gender', 'Wanita')
            ->join('sales', 'customers.id', '=', 'sales.customer_id')
            ->selectRaw('customers.*, MAX(sales.total_amount) as max_purchase')
            ->groupBy('customers.id')
            ->orderByDesc('max_purchase')
            ->first();

        return response()->json($topPurchase);
    }

    // 8. Barang dengan rata-rata penjualan terkecil
    public function lowestAverageSalesProduct()
    {
        $product = Product::select('products.*')
            ->leftJoin('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->selectRaw('products.*, 
                        COALESCE(AVG(sale_items.quantity), 0) as avg_sales')
            ->groupBy('products.id')
            ->orderBy('avg_sales')
            ->first();

        return response()->json($product);
    }

    // 9. Karyawan dengan rata-rata penjualan terbesar per bulan
    public function topEmployeeMonthlyAverage()
    {
        $employees = Employee::select('employees.*')
            ->join('sales', 'employees.id', '=', 'sales.employee_id')
            ->selectRaw('employees.*, 
                        MONTH(sales.sale_date) as month,
                        YEAR(sales.sale_date) as year,
                        AVG(sales.total_amount) as monthly_avg')
            ->groupBy('employees.id', 'month', 'year')
            ->orderByDesc('monthly_avg')
            ->get();

        return response()->json($employees);
    }

    // 10. Karyawan yang berhak menerima bonus tahunan
    public function annualBonusEligibility()
    {
        $currentYear = Carbon::now()->year;

        // Total penjualan tertinggi per bulan untuk setiap karyawan
        $monthlyMaxSales = Sale::whereYear('sale_date', $currentYear)
            ->select('employee_id', 
                DB::raw('MONTH(sale_date) as month'),
                DB::raw('MAX(total_amount) as max_sales')
            )
            ->groupBy('employee_id', 'month')
            ->get();

        // Rata-rata seluruh penjualan tahun ini
        $yearlyAverage = Sale::whereYear('sale_date', $currentYear)
            ->avg('total_amount');

        // Penjualan di atas rata-rata untuk setiap karyawan
        $aboveAverageSales = Sale::whereYear('sale_date', $currentYear)
            ->where('total_amount', '>', $yearlyAverage)
            ->select('employee_id', 
                DB::raw('SUM(total_amount) as total_above_avg')
            )
            ->groupBy('employee_id')
            ->get();

        // Hitung bonus
        $employees = Employee::with(['sales' => function($query) use ($currentYear) {
            $query->whereYear('sale_date', $currentYear);
        }])->get();

        $bonusData = $employees->map(function($employee) use ($monthlyMaxSales, $aboveAverageSales, $yearlyAverage) {
            $employeeMonthlyMax = $monthlyMaxSales->where('employee_id', $employee->id);
            $totalMonthlyBonus = $employeeMonthlyMax->sum('max_sales') * 0.10;

            $employeeAboveAvg = $aboveAverageSales->where('employee_id', $employee->id)->first();
            $aboveAvgBonus = $employeeAboveAvg ? $employeeAboveAvg->total_above_avg * 0.05 : 0;

            $annualBonus = $totalMonthlyBonus + $aboveAvgBonus;

            return [
                'employee' => $employee,
                'monthly_bonus_component' => $totalMonthlyBonus,
                'above_avg_bonus_component' => $aboveAvgBonus,
                'total_annual_bonus' => $annualBonus
            ];
        });

        return response()->json($bonusData);
    }
}