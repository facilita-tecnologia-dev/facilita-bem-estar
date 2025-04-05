<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\Login\EmployeeLoginController;
use App\Http\Controllers\Auth\Login\ExternalManagerLoginController;
use App\Http\Controllers\Auth\Login\HealthWorkerLoginController;
use App\Http\Controllers\Auth\Login\InternalManagerLoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Private\CompanyProfileController;
use App\Http\Controllers\Private\CreateEmployeeController;
use App\Http\Controllers\Private\Dashboard\DashboardController;
use App\Http\Controllers\Private\Dashboard\TestResultsListController;
use App\Http\Controllers\Private\Dashboard\TestResultsPerDepartmentController;
use App\Http\Controllers\Private\EmployeeProfileController;
use App\Http\Controllers\Private\EmployeesListController;
use App\Http\Controllers\Private\ImportUsersController;
use App\Http\Controllers\Private\TestResultsController;
use App\Http\Controllers\Private\TestsController;
use App\Http\Controllers\Private\UpdateCompanyProfileController;
use App\Http\Controllers\Private\UpdateEmployeeProfileController;
use App\Http\Controllers\Private\WelcomeController;
use App\Http\Controllers\Public\PresentationController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\GuestMiddleware;
use GuzzleHttp\Promise\Create;
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

        Route::view('/escolha-teste', 'choose-test')->name('choose-test');

        /* Rotas do colaborador */

        Route::get('/bem-vindo', [WelcomeController::class, 'index'])->name('welcome');
        Route::get('/bem-vindo/start', [WelcomeController::class, 'startTests'])->name('welcome.start');
                
        // Rotas dos formulários de teste
        Route::get('/teste/{test?}', TestsController::class)->name('test');

        Route::post('/teste/{test}/submit', [TestsController::class, 'handleTestSubmit'])->name('test.submit');
        
        // Rotas do resultado individual
        Route::get('/resultado', [TestResultsController::class, 'index'])->name('test-results');
        
    /* ------------------------------------------------------------------------------------------------ */
    
    /* Rotas do gestor */
   
    // Rotas do Dashboard Geral
    Route::middleware(AdminMiddleware::class)->group(function(){
        Route::get('/perfil-empresa', CompanyProfileController::class)->name('company-profile');

        Route::get('/perfil-empresa/editar', UpdateCompanyProfileController::class)->name('company-profile.update');
        Route::post('/perfil-empresa/editar', [UpdateCompanyProfileController::class, 'updateCompanyProfile']);

        Route::get('/empresa/lista-colaboradores', EmployeesListController::class)->name('employees-list');
        
        Route::get('/importar-colaboradores', ImportUsersController::class)->name('import-employees');
        Route::post('/importar-colaboradores', [ImportUsersController::class, 'importUsers']);

        Route::get('/dashboard', DashboardController::class)->name('dashboard.charts');
    
        Route::get('/dashboard/{test}', TestResultsPerDepartmentController::class)->name('dashboard.test-result-per-department');
        
        Route::get('/dashboard/{test}/lista', TestResultsListController::class)->name('dashboard.test-results-list');
        
        Route::get('/colaborador/create', CreateEmployeeController::class)->name('employee-profile.create');
        Route::post('/colaborador/create', [CreateEmployeeController::class, 'createEmployeeProfile']);

        Route::get('/colaborador/{employee}', EmployeeProfileController::class)->name('employee-profile');

        Route::get('/colaborador/{employee}/update', UpdateEmployeeProfileController::class)->name('employee-profile.update');
        Route::post('/colaborador/{employee}/update', [UpdateEmployeeProfileController::class, 'updateEmployeeProfile']);
        

    });

    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});

Route::view('/components', 'components');