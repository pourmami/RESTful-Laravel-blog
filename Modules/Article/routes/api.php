<?php

use Illuminate\Support\Facades\Route;
use Modules\Article\app\Http\Controllers\ArticleController;

Route::get('articles', [ArticleController::class, 'index']);
Route::get('articles/{slug}', [ArticleController::class, 'show']);
Route::middleware(['auth:sanctum', 'api_role:admin'])->group(function () {
    Route::resource('/articles', ArticleController::class)
        ->except(['index', 'create', 'edit', 'show']);
});
