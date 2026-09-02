<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\PricingRuleController;
use App\Models\PricingRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/auth')->group(function() {
    Route::get('/', [AuthController::class, 'index']);
    Route::get('/{user}', [AuthController::class, 'show']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    // transactions
    Route::get('/bookings', [BookingController::class, 'index']);
});


// PRODUCT
Route::get('products/search/{court}', [CourtController::class, 'search']);
Route::get('/courts/{court}', [CourtController::class, 'show']);
Route::get('/courts', [CourtController::class, 'index']);

Route::get('/pricings', [PricingRuleController::class, 'index']);

Route::post('/bookings', [BookingController::class, 'store']);
