<?php

use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Dashboard\IndividualTestListController;
use App\Http\Controllers\Admin\Dashboard\IndividualTestResultController;
use App\Http\Controllers\Admin\UserInfoController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\InitialController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Users\TestResultsController;
use App\Http\Controllers\Users\TestsController;
use App\Http\Controllers\Users\WelcomeController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\GuestMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware(GuestMiddleware::class)->group(function(){
    Route::get('/', [InitialController::class, 'index'])->name('auth.initial');

    Route::get('/login', [UserLoginController::class, 'index'])->name('auth.login.user');
    Route::post('/login', [UserLoginController::class, 'attemptLogin']);

    Route::get('/login/admin', [AdminLoginController::class, 'index'])->name('auth.login.admin');
    Route::post('/login/admin', [AdminLoginController::class, 'attemptLogin']);
});
   
  
Route::middleware(AuthMiddleware::class)->group(function(){
    /* Rotas do colaborador */

    Route::middleware(UserMiddleware::class)->group(function(){
        Route::get('/bem-vindo', [WelcomeController::class, 'index'])->name('welcome');
                
        // Rotas dos formulários de teste
        Route::get('/teste/{test}', [TestsController::class, 'index'])->name('test');
        Route::post('/teste/{test}/submit', [TestsController::class, 'handleTestSubmit'])->name('test.submit');
        
        // Rotas do resultado individual
        Route::get('/resultado', [TestResultsController::class, 'index'])->name('test-results');
    });
    /* ------------------------------------------------------------------------------------------------ */
    
    /* Rotas do gestor */
   
    // Rotas do Dashboard Geral
    Route::middleware(AdminMiddleware::class)->group(function(){
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('general-results.dashboard');
    
        Route::get('/dashboard/{test}', [IndividualTestResultController::class, 'index'])->name('test-results.dashboard');
        
        Route::get('/dashboard/{test}/lista', [IndividualTestListController::class, 'index'])->name('test-results-list.dashboard');
        
        Route::get('/user/{user}', [UserInfoController::class, 'index'])->name('user.info');
    });

    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});