<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\IndividualTestListController;
use App\Http\Controllers\Dashboard\IndividualTestResultController;
use App\Http\Controllers\TestResultsController;
use App\Http\Controllers\TestsController;
use App\Http\Middleware\AutoLoginMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(AutoLoginMiddleware::class)->group(function(){
    Route::view('/', 'welcome')->name('welcome');

    // Rotas dos formulários de teste
    Route::get('/teste/{test}', [TestsController::class, 'index'])->name('test');
    Route::post('/teste/{test}/submit', [TestsController::class, 'handleTestSubmit'])->name('test.submit');
    

    // Rotas do Dashboard Geral
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('general-results.dashboard');

    Route::get('/dashboard/{test}', [IndividualTestResultController::class, 'index'])->name('test-results.dashboard');
    
    Route::get('/dashboard/{test}/lista', [IndividualTestListController::class, 'index'])->name('test-results-list.dashboard');
    
    // Rotas do resultado individual
    Route::get('/resultado', [TestResultsController::class, 'index'])->name('test-results');
});