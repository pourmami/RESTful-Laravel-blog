<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\app\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {

    Route::post('send-code', [AuthController::class, 'sendActivationCode']);
    Route::post('verify-code', [AuthController::class, 'verifyActivationCode']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('complete-register', [AuthController::class, 'completeRegister']);
    });

});
