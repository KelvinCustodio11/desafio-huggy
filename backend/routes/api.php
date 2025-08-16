<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::middleware('api')->group(function () {
    Route::prefix('clients')->group(function () {
        // CRUD de clientes
        Route::apiResource('/', ClientController::class)
            ->parameters(['' => 'client']) // ajusta parâmetro vazio
            ->whereNumber('client');

        // Rotas extras
        Route::get('search', [ClientController::class, 'searchByNameOrPhone']);
    });
});
