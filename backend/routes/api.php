<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\HuggyAuthController;

Route::middleware('api')->group(function () {
    Route::prefix('clients')->group(function () {
        // CRUD de clientes
        Route::apiResource('/', ClientController::class)
            ->parameters(['' => 'client']) // ajusta parâmetro vazio
            ->whereNumber('client');

        // Rotas extras
        Route::get('search', [ClientController::class, 'searchByNameOrPhone']);
        // Route::get('report/insights', [ClientController::class, 'report']);
        // Route::post('{client}/call', [ClientController::class, 'call'])
        //     ->whereNumber('client');
    });

    Route::prefix('auth')->group(function () {
        Route::prefix( 'huggy')->group(function () {
            Route::get('/login', [HuggyAuthController::class, 'redirect']);
            Route::get('/callback', [HuggyAuthController::class, 'callback']);
        });
    });

    Route::get('/huggy/contacts/sync', \App\Http\Controllers\HuggySyncController::class)
        ->middleware('auth:sanctum');
});
