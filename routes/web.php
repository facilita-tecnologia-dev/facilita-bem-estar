<?php

use App\Http\Controllers\TestsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');
Route::get('/teste/{test}', [TestsController::class, 'index'])->name('test');
Route::post('/teste/{test}', [TestsController::class, 'handleTestSubmitted'])->name('submit-test');
