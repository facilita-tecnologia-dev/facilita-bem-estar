<?php

use App\Http\Controllers\Auth\Login\EmployeeLoginController;
use App\Http\Controllers\Auth\Login\ExternalManagerLoginController;
use App\Http\Controllers\Auth\Login\HealthWorkerLoginController;
use App\Http\Controllers\Auth\Login\InternalManagerLoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Private\CompanyMetricsController;
use App\Http\Controllers\Private\CompanyController;
use App\Http\Controllers\Private\Dashboard\DashboardController;
use App\Http\Controllers\Private\Dashboard\RisksController;
use App\Http\Controllers\Private\Dashboard\TestResultsListController;
use App\Http\Controllers\Private\Dashboard\TestResultsPerDepartmentController;
use App\Http\Controllers\Private\ImportUsersController;
use App\Http\Controllers\Private\TestsController;
use App\Http\Controllers\Private\UserController;
use App\Http\Controllers\Public\PresentationController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\GuestMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware(GuestMiddleware::class)->group(function(){
    Route::get('/', [PresentationController::class, 'index'])->name('presentation');

    Route::get('/login/colaborador', EmployeeLoginController::class)->name('auth.login.employee');
    Route::post('/login/colaborador', [EmployeeLoginController::class, 'attemptLogin']);

    Route::get('/login/gestor-interno', InternalManagerLoginController::class)->name('auth.login.internal-manager');
    Route::post('/login/gestor-interno', [InternalManagerLoginController::class, 'attemptLogin']);

    // Route::get('/login/gestor-externo', ExternalManagerLoginController::class)->name('auth.login.external-manager');
    
    // Route::get('/login/profissional-saude', HealthWorkerLoginController::class)->name('auth.login.health-worker');
});
   
  
Route::middleware(AuthMiddleware::class)->group(function(){
    Route::get('/escolha-teste', [TestsController::class, 'showChooseScreen'])->name('choose-test');
        
    Route::get('/teste/{test?}', TestsController::class)->name('test');
    Route::post('/teste/{test}/submit', [TestsController::class, 'handleTestSubmit'])->name('test.submit');

    Route::resource('company', CompanyController::class);
    // Route::get('/employee/create-first/{company}', [UserController::class, 'createFirstUser'])->name('user.create-first');
    Route::resource('user', UserController::class);

    Route::get('/indicadores', CompanyMetricsController::class)->name('company-metrics');
    Route::post('/indicadores', [CompanyMetricsController::class, 'storeMetrics']);
    
    Route::get('/importar-colaboradores', ImportUsersController::class)->name('import-employees.show');
    Route::post('/importar-colaboradores/{company}', [ImportUsersController::class, 'importUsers'])->name('import-employees.import');

    Route::get('/dashboard', DashboardController::class)->name('dashboard.charts');
    Route::get('/dashboard/riscos', RisksController::class)->name('dashboard.risks');
    Route::get('/dashboard/riscos/inventario', [RisksController::class, 'generatePDF'])->name('dashboard.risks.pdf');
    Route::get('/dashboard/{test}', TestResultsPerDepartmentController::class)->name('dashboard.test-result-per-department');
    Route::get('/dashboard/{test}/lista', TestResultsListController::class)->name('dashboard.test-results-list');

    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});
