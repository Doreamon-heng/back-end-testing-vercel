<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Web UI disabled: REST API on
// use App\Http\Controllers\api\PaymentController;

// Bakong test page (no auth — for local/sandbox testing only, remove before production)
// Route::get('/bakong-test', function () {
//     return view('bakong_test');
// });
// Route::post('/bakong-test/pay', [PaymentController::class, 'createBakongPayment'])->name('bakong.test.pay');
// Route::get('/bakong-test/status/{id}', [PaymentController::class, 'checkPaymentStatus'])->name('bakong.test.status');
Route::get('/api-tools', function () {
    return view('api-tools.index');
})->name('api.tools');