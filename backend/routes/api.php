<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HuggySyncController;
use App\Http\Controllers\Auth\HuggyAuthController;

Route::middleware('api')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
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

        Route::get('/huggy/contacts/sync', HuggySyncController::class);
    });
    Route::post('/huggy/contacts/webhook', [HuggySyncController::class, 'syncClientWebhook']);

    Route::prefix('auth')->group(function () {
        Route::prefix( 'huggy')->group(function () {
            Route::get('/login', [HuggyAuthController::class, 'redirect'])->name('login');
            Route::get('/callback', [HuggyAuthController::class, 'callback']);
        });
    });
});
