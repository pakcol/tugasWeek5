// routes/api.php
Route::prefix('reports')->group(function () {
    Route::get('top-products', [ReportController::class, 'top5Products']);
    Route::get('top-category', [ReportController::class, 'topCategory']);
    Route::get('top-spenders', [ReportController::class, 'top3Spenders']);
    Route::get('top-buyer', [ReportController::class, 'topBuyer']);
    Route::get('customers-above-average', [ReportController::class, 'customersAboveMonthlyAverage']);
    Route::get('last-months-average', [ReportController::class, 'last3MonthsAverage']);
    Route::get('top-female-customer', [ReportController::class, 'topFemaleCustomerPurchase']);
    Route::get('lowest-sales-product', [ReportController::class, 'lowestAverageSalesProduct']);
    Route::get('top-employees', [ReportController::class, 'topEmployeeMonthlyAverage']);
    Route::get('annual-bonus', [ReportController::class, 'annualBonusEligibility']);
});

// routes/web.php
Route::get('/', function () {
    $reportController = new ReportController();
    
    $data = [
        'topProducts' => $reportController->top5Products()->getData(),
        'topCategory' => $reportController->topCategory()->getData(),
        'topSpenders' => $reportController->top3Spenders()->getData(),
        'topBuyer' => $reportController->topBuyer()->getData(),
    ];
    
    return view('welcome', $data);
});