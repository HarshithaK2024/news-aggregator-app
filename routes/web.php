<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/news', function () {
    return view('welcome'); // Or return a response
});

Route::get('/fetch-news', [NewsController::class, 'fetchNews']);