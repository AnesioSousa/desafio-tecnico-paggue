<?php
// src/database/routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProducerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;


Route::prefix('v1')->group(function () {
    // Public endpoints
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    // public...
    Route::middleware('auth:api')->group(function () {
        // admin-only…
        Route::middleware('role:admin')
            ->apiResource('producers', ProducerController::class);

        // producers & admins (full CRUD)…
        Route::middleware('role:producer|admin')->group(function () {
            Route::apiResource('events', EventController::class);
            Route::apiResource('sectors', SectorController::class);
            Route::apiResource('batches', BatchController::class);
            Route::apiResource('coupons', CouponController::class);
            Route::apiResource('payments', PaymentController::class);
            Route::apiResource('notifications', NotificationController::class);
        });

        // orders & tickets: **all** authenticated users hit these,
        // but your policies will gate create/read/update/delete
        Route::apiResource('orders', OrderController::class);
        Route::apiResource('tickets', TicketController::class);
    });
});



/*  // Public endpoints
  Route::post('register', [AuthController::class, 'register']);
  Route::post('login', [AuthController::class, 'login']);

  // Protected (need Bearer token)
  Route::middleware('auth:api')->group(function () {

});
*/