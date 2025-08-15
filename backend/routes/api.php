<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::middleware('api')->group(function () {
    // Define API routes for ClientController
    Route::apiResource('clients', ClientController::class);
});
