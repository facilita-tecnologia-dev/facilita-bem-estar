<?php

use App\Http\Controllers\Auth\ChooseCompanyToLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Private\CompanyController;
use App\Http\Controllers\Private\CompanyMetricsController;
use App\Http\Controllers\Private\Dashboard\Demographics\DemographicsMainController;
use App\Http\Controllers\Private\Dashboard\Organizational\OrganizationalAnswersController;
use App\Http\Controllers\Private\Dashboard\Organizational\OrganizationalMainController;
use App\Http\Controllers\Private\Dashboard\Psychosocial\PsychosocialMainController;
use App\Http\Controllers\Private\Dashboard\Psychosocial\PsychosocialResultsByDepartmentController;
use App\Http\Controllers\Private\Dashboard\Psychosocial\PsychosocialResultsListController;
use App\Http\Controllers\Private\Dashboard\Psychosocial\PsychosocialRisksController;
use App\Http\Controllers\Private\TestsController;
use App\Http\Controllers\Private\UserController;
use App\Http\Controllers\Private\UserFeedbackController;
use App\Http\Controllers\Public\PresentationController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\GuestMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(GuestMiddleware::class)->group(function () {
    Route::get('/', [PresentationController::class, 'index'])->name('presentation');

    Route::get('/register', RegisterController::class)->name('auth.register');
    Route::post('/register/internal/user', [RegisterController::class, 'attemptInternalUserRegister'])->name('auth.register.internal.user');
    Route::get('/register/internal/company', [RegisterController::class, 'showCompanyRegister'])->name('auth.register.internal.company');
    Route::post('/register/internal/company', [RegisterController::class, 'attemptCompanyRegister']);
    Route::post('/register/external/user', [RegisterController::class, 'attemptExternalRegister'])->name('auth.register.external');

    Route::get('/login', LoginController::class)->name('auth.login');
    Route::post('/login/internal/', [LoginController::class, 'attemptInternalLogin'])->name('auth.login.internal');

    Route::get('/login/internal/companies', ChooseCompanyToLoginController::class)->name('auth.login.show-companies');
    Route::post('/login/internal/companies/{company?}', [ChooseCompanyToLoginController::class, 'attemptInternalLoginWithCompany'])->name('auth.login.internal.with-company');

    Route::post('/login/external', [LoginController::class, 'attemptExternalLogin'])->name('auth.login.external');
});

Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get('/choose-test', [TestsController::class, 'showChoose'])->name('choose-test');
    Route::get('/collection/{collection}/test/{test?}', TestsController::class)->name('test');
    Route::post('/collection/{collection}/test/{test}/submit', [TestsController::class, 'handleTestSubmit'])->name('test.submit');
    Route::get('/feedback', [UserFeedbackController::class, 'create'])->name('feedbacks.create');
    Route::post('/feedback', [UserFeedbackController::class, 'store']);
    Route::view('/thanks', 'private.tests.thanks')->name('test.thanks');

    Route::middleware('is-internal-manager')->group(function(){
        Route::resource('company', CompanyController::class);
        Route::get('/user/import', [UserController::class, 'showImport'])->name('user.import');
        Route::post('/user/import', [UserController::class, 'import']);
        Route::resource('user', UserController::class);

        Route::middleware('can-access-psychosocial')->group(function(){
            Route::get('/indicadores', CompanyMetricsController::class)->name('company-metrics');
            Route::post('/indicadores', [CompanyMetricsController::class, 'storeMetrics']);
        });

        Route::prefix('dashboard')->group(function () {
            Route::get('demographics', DemographicsMainController::class)->name('dashboard.demographics');

            Route::middleware('can-access-organizational')->group(function(){
                Route::get('organizational', OrganizationalMainController::class)->name('dashboard.organizational-climate');
                Route::get('organizational/answers', OrganizationalAnswersController::class)->name('dashboard.organizational-climate.by-answers');
            
                Route::get('feedbacks', [UserFeedbackController::class, 'index'])->name('feedbacks.index');
                Route::get('feedbacks/{feedback}', [UserFeedbackController::class, 'show'])->name('feedbacks.show');
            });

            Route::middleware('can-access-psychosocial')->group(function(){
                Route::get('psychosocial', PsychosocialMainController::class)->name('dashboard.psychosocial');
                Route::get('psychosocial/risks', PsychosocialRisksController::class)->name('dashboard.psychosocial.risks');
                Route::get('psychosocial/risks/inventory', [PsychosocialRisksController::class, 'generatePDF'])->name('dashboard.psychosocial.risks.pdf');
                Route::get('psychosocial/{test}', PsychosocialResultsByDepartmentController::class)->name('dashboard.psychosocial.by-department');
                Route::get('psychosocial/{test}/list', PsychosocialResultsListController::class)->name('dashboard.psychosocial.list');
            });
        });
    });


    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});
