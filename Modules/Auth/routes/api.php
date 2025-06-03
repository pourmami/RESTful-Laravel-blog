<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\app\Http\Controllers\AuthController;
use Modules\Auth\app\Http\Controllers\ForgetPasswordController;

Route::prefix('auth')->group(function () {

    Route::post('send-code',    [AuthController::class, 'sendActivationCode']);
    Route::post('verify-code',  [AuthController::class, 'verifyActivationCode']);

    Route::middleware(['auth:sanctum', 'ability:complete-register'])
        ->post('complete-register', [AuthController::class, 'completeRegister']);

    Route::post('/forgot-password', [ForgetPasswordController::class, 'requestReset']);
    Route::post('/reset-password',  [ForgetPasswordController::class, 'resetPassword']);
});
