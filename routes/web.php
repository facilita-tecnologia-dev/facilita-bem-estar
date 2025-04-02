<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Dashboard\IndividualTestListController;
use App\Http\Controllers\Admin\Dashboard\TestResultsPerDepartmentController;
use App\Http\Controllers\Admin\UserInfoController;
use App\Http\Controllers\Auth\Login\EmployeeLoginController;
use App\Http\Controllers\Auth\Login\ExternalManagerLoginController;
use App\Http\Controllers\Auth\Login\HealthWorkerLoginController;
use App\Http\Controllers\Auth\Login\InternalManagerLoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\Users\TestResultsController;
use App\Http\Controllers\Users\TestsController;
use App\Http\Controllers\Users\WelcomeController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\GuestMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [PresentationController::class, 'index'])->name('presentation');

Route::middleware(GuestMiddleware::class)->group(function(){
    Route::get('/login/colaborador', EmployeeLoginController::class)->name('auth.login.employee');
    Route::post('/login/colaborador', [EmployeeLoginController::class, 'attemptLogin']);

    Route::get('/login/gestor-interno', InternalManagerLoginController::class)->name('auth.login.internal-manager');
    Route::post('/login/gestor-interno', [InternalManagerLoginController::class, 'attemptLogin']);

    Route::get('/login/gestor-externo', ExternalManagerLoginController::class)->name('auth.login.external-manager');
    
    Route::get('/login/profissional-saude', HealthWorkerLoginController::class)->name('auth.login.health-worker');
});
   
  
Route::middleware(AuthMiddleware::class)->group(function(){

        Route::view('/testes', 'choose-test')->name('choose-test');


        /* Rotas do colaborador */

        Route::get('/bem-vindo', [WelcomeController::class, 'index'])->name('welcome');
        Route::get('/bem-vindo/start', [WelcomeController::class, 'startTests'])->name('welcome.start');
                
        // Rotas dos formulários de teste
        Route::get('/teste/{test}', [TestsController::class, 'index'])->name('test');
        Route::post('/teste/{test}/submit', [TestsController::class, 'handleTestSubmit'])->name('test.submit');
        
        // Rotas do resultado individual
        Route::get('/resultado', [TestResultsController::class, 'index'])->name('test-results');
        
    /* ------------------------------------------------------------------------------------------------ */
    
    /* Rotas do gestor */
   
    // Rotas do Dashboard Geral
    Route::middleware(AdminMiddleware::class)->group(function(){
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.welcome');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('general-results.dashboard');
    
        Route::get('/dashboard/{test}', [TestResultsPerDepartmentController::class, 'index'])->name('test-results.dashboard');
        
        Route::get('/dashboard/{test}/lista', [IndividualTestListController::class, 'index'])->name('test-results-list.dashboard');
        
        Route::get('/user/{user}', [UserInfoController::class, 'index'])->name('user.info');
    });

    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});

Route::view('/components', 'components');