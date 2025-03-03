<?php

use App\Http\Controllers\TestsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');
Route::get('/teste/{test}', [TestsController::class, 'index'])->name('test');
Route::post('/teste/{test}/submit', [TestsController::class, 'handleTestSubmitted'])->name('test.submit');



// // Rota para exibir os resultados finais
// Route::get('/test/results', [TestController::class, 'results'])->name('test.results');