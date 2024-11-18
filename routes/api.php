<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\UserPreferenceController;

Route::get('/print', [AuthController::class, 'print']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware(['auth:sanctum', 'check.password.reset'])->group(function () {
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });
//     // Article routes
//     // Route::get('/articles', [ArticleController::class, 'index']);
//     // Route::get('/articles/{article}', [ArticleController::class, 'show']);

//     // User preferences routes
//     Route::post('/preferences', [UserPreferenceController::class, 'store']);
//     Route::get('/feed', [UserPreferenceController::class, 'getPersonalizedFeed']);
// });