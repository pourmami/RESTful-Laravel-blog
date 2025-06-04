<?php

use Illuminate\Support\Facades\Route;
use Modules\Article\app\Http\Controllers\ArticleController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/articles', [ArticleController::class, 'store']);
});
