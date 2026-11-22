<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CarrierController;
use App\Http\Controllers\Api\ExceptionController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ShipmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API Routes - Trackivo
|--------------------------------------------------------------------------
*/

// Public Authentication Endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Endpoints (auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Auth & User Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Shipments
    Route::get('/shipments', [ShipmentController::class, 'index']);
    Route::post('/shipments', [ShipmentController::class, 'store']);
    Route::get('/shipments/{shipment}', [ShipmentController::class, 'show']);
    Route::match(['put', 'patch'], '/shipments/{shipment}', [ShipmentController::class, 'update']);
    Route::delete('/shipments/{shipment}', [ShipmentController::class, 'destroy']);

    // Tracking
    Route::get('/shipments/{shipment}/tracking', [ShipmentController::class, 'tracking']);
    Route::post('/shipments/{shipment}/tracking', [ShipmentController::class, 'storeTracking']);

    // Exceptions
    Route::get('/shipments/{shipment}/exceptions', [ExceptionController::class, 'index']);
    Route::post('/shipments/{shipment}/exceptions', [ExceptionController::class, 'store']);
    Route::patch('/exceptions/{exception}', [ExceptionController::class, 'update']);

    // Carriers
    Route::get('/carriers', [CarrierController::class, 'index']);
    Route::post('/carriers', [CarrierController::class, 'store']);

    // Locations
    Route::get('/locations', [LocationController::class, 'index']);
    Route::post('/locations', [LocationController::class, 'store']);
});
