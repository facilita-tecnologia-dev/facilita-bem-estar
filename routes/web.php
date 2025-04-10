<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Private\CompanyController;
use App\Http\Controllers\Private\CompanyMetricsController;
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

Route::middleware(GuestMiddleware::class)->group(function () {
    Route::get('/', [PresentationController::class, 'index'])->name('presentation');

    Route::get('/register', RegisterController::class)->name('auth.register');
    Route::post('/register/internal', [RegisterController::class, 'attemptInternalRegister'])->name('auth.register.internal');
    Route::post('/register/external', [RegisterController::class, 'attemptExternalRegister'])->name('auth.register.external');

    Route::get('/login', LoginController::class)->name('auth.login');
    Route::post('/login/internal', [LoginController::class, 'attemptInternalLogin'])->name('auth.login.internal');
    Route::post('/login/external', [LoginController::class, 'attemptExternalLogin'])->name('auth.login.external');
});

Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get('/choose-test', [TestsController::class, 'showChoose'])->name('choose-test');
    Route::get('/test/{test?}', TestsController::class)->name('test');
    Route::post('/test/{test}/submit', [TestsController::class, 'handleTestSubmit'])->name('test.submit');

    Route::resource('company', CompanyController::class);
    Route::resource('user', UserController::class);

    Route::get('/user/import', [UserController::class, 'showImport'])->name('user.import');
    Route::post('/user/import', [UserController::class, 'import']);

    Route::get('/indicadores', CompanyMetricsController::class)->name('company-metrics');
    Route::post('/indicadores', [CompanyMetricsController::class, 'storeMetrics']);

    Route::prefix('dashboard')->group(function(){
        Route::get('/', DashboardController::class)->name('dashboard.charts');
        Route::get('/{test}', TestResultsPerDepartmentController::class)->name('dashboard.test-result-per-department');
        Route::get('/{test}/list', TestResultsListController::class)->name('dashboard.test-results-list');
        Route::get('/risks', RisksController::class)->name('dashboard.risks');
        Route::get('/risks/inventory', [RisksController::class, 'generatePDF'])->name('dashboard.risks.pdf');
    });

    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});
