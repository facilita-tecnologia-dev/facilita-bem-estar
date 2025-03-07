<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TestResultsController;
use App\Http\Controllers\TestsController;
use App\Http\Middleware\AutoLoginMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(AutoLoginMiddleware::class)->group(function(){
    Route::view('/', 'welcome')->name('welcome');
    Route::get('/teste/{test}', [TestsController::class, 'index'])->name('test');
    Route::post('/teste/{test}/submit', [TestsController::class, 'handleTestSubmitted'])->name('test.submit');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('general-results.dashboard');
    Route::get('/dashboard/{test}', [DashboardController::class, 'renderIndividualTestStats'])->name('test-results.dashboard');
    Route::get('/dashboard/{test}/lista', [DashboardController::class, 'renderIndividualTestList'])->name('test-results-list.dashboard');
    
    Route::get('/resultado', [TestResultsController::class, 'index'])->name('test-results');
});