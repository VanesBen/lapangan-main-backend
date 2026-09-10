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
    
    Route::patch('/courts/{court}', [CourtController::class, 'update']);
    Route::post('/courts', [CourtController::class, 'store']);
    Route::get('admin/courts', [CourtController::class, 'index']);


    Route::patch('/prices/{price}', [PricingRuleController::class, 'update']);
    Route::delete('/prices/{price}', [PricingRuleController::class, 'destroy']);
    Route::post('/prices', [PricingRuleController::class, 'store']);
});


// PRODUCT
Route::get('products/search/{court}', [CourtController::class, 'search']);
Route::get('/courts/{court}', [CourtController::class, 'show']);

Route::get('/courts/{court}/availability', [CourtController::class, 'checkAvailability']);

Route::get('/courts', [CourtController::class, 'publicIndex']);

Route::get('/pricings', [PricingRuleController::class, 'index']);

Route::post('/bookings', [BookingController::class, 'store']);
