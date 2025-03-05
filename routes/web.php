<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TestsController;
use App\Http\Middleware\AutoLoginMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(AutoLoginMiddleware::class)->group(function(){
    Route::view('/', 'welcome')->name('welcome');
    Route::get('/teste/{test}', [TestsController::class, 'index'])->name('test');
    Route::post('/teste/{test}/submit', [TestsController::class, 'handleTestSubmitted'])->name('test.submit');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('results.dashboard');
});