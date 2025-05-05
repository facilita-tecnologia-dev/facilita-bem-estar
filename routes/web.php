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
    Route::get('/', [PresentationController::class, 'index'])->name('apresentacao');

    Route::get('/cadastro', RegisterController::class)->name('auth.cadastro');
    Route::post('/cadastro/usuario-interno', [RegisterController::class, 'attemptInternalUserRegister'])->name('auth.cadastro.usuario-interno');

    Route::get('/cadastro/empresa', [RegisterController::class, 'showCompanyRegister'])->name('auth.cadastro.empresa');
    Route::post('/cadastro/empresa', [RegisterController::class, 'attemptCompanyRegister']);

    Route::post('/cadastro/usuario-externo', [RegisterController::class, 'attemptExternalRegister'])->name('auth.cadastro.usuario-externo');

    Route::get('/login', LoginController::class)->name('auth.login');
    Route::post('/login/usuario-interno', [LoginController::class, 'attemptInternalLogin'])->name('auth.login.usuario-interno');

    Route::get('/login/usuario-interno/escolher-empresa', ChooseCompanyToLoginController::class)->name('auth.login.usuario-interno.escolher-empresa');
    Route::post('/login/usuario-interno/escolher-empresa/{company?}', [ChooseCompanyToLoginController::class, 'attemptInternalLoginWithCompany'])->name('auth.login.usuario-interno.entrar-com-empresa');

    Route::post('/login/usuario-externo', [LoginController::class, 'attemptExternalLogin'])->name('auth.login.usuario-externo');
});

Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get('/escolher-teste', [TestsController::class, 'showChoose'])->name('escolher-teste');

    Route::get('/colecao/{collection}/teste/{test?}', TestsController::class)->name('responder-teste');
    Route::post('/colecao/{collection}/teste/{test}/enviar', [TestsController::class, 'handleTestSubmit'])->name('enviar-teste');
    
    Route::get('/comentario', [UserFeedbackController::class, 'create'])->name('feedbacks.create');
    Route::post('/comentario', [UserFeedbackController::class, 'store']);
    
    Route::view('/obrigado', 'private.tests.thanks')->name('test.thanks');

    Route::middleware('is-internal-manager')->group(function(){
        Route::resource('company', CompanyController::class);
        Route::resource('user', UserController::class);
        
        Route::get('/usuario/importar', [UserController::class, 'showImport'])->name('user.import');
        Route::post('/usuario/importar', [UserController::class, 'import']);

        Route::middleware('can-access-psychosocial')->group(function(){
            Route::get('/indicadores', CompanyMetricsController::class)->name('company-metrics');
            Route::post('/indicadores', [CompanyMetricsController::class, 'storeMetrics']);
        });

        Route::prefix('dashboard')->group(function () {
            Route::get('demografia', DemographicsMainController::class)->name('dashboard.demographics');

            Route::middleware('can-access-organizational')->group(function(){
                Route::get('clima-organizacional', OrganizationalMainController::class)->name('dashboard.organizational-climate');
                Route::post('clima-organizacional', [OrganizationalMainController::class, 'createPDFReport'])->name('dashboard.organizational-climate.report');
                
                Route::get('clima-organizacional/respostas', OrganizationalAnswersController::class)->name('dashboard.organizational-climate.by-answers');
                Route::get('clima-organizacional/respostas/pdf', [OrganizationalAnswersController::class, 'createPDFReport'])->name('dashboard.organizational-climate.by-answers.report');
            
                Route::get('comentarios', [UserFeedbackController::class, 'index'])->name('feedbacks.index');
                Route::get('comentarios/{feedback}', [UserFeedbackController::class, 'show'])->name('feedbacks.show');
            });

            Route::middleware('can-access-psychosocial')->group(function(){
                Route::get('psicossocial', PsychosocialMainController::class)->name('dashboard.psychosocial');
                Route::get('psicossocial/riscos', PsychosocialRisksController::class)->name('dashboard.psychosocial.risks');
                Route::get('psicossocial/riscos/pdf', [PsychosocialRisksController::class, 'generatePDF'])->name('dashboard.psychosocial.risks.pdf');
                
                Route::get('psicossocial/{test}/departamentos', PsychosocialResultsByDepartmentController::class)->name('dashboard.psychosocial.by-department');
                Route::get('psicossocial/{test}/lista', PsychosocialResultsListController::class)->name('dashboard.psychosocial.list');
            });
        });
    });


    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});
