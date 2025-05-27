<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdutorController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\SetorController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\IngressoController;
use App\Http\Controllers\CupomController;

Route::group(['prefix' => 'v1'], function () {
    // Public endpoints
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Protected (need Bearer token)
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);

        Route::apiResource('produtores', ProdutorController::class)
            ->parameters(['produtores' => 'produtor']);

        Route::apiResource('eventos', EventoController::class);
        Route::apiResource('setores', SetorController::class);
        Route::apiResource('lotes', LoteController::class);
        Route::apiResource('ingressos', IngressoController::class);
        Route::apiResource('cupons', CupomController::class);
    });
});
