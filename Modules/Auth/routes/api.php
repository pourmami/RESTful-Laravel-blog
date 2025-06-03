<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\app\Http\Controllers\AuthController;

Route::post('activation-code', [AuthController::class, 'sendActivationCode']);
Route::post('verify-activation-code', [AuthController::class, 'verifyActivationCode']);
