<?php

use App\Exports\CommentsExport;
use App\Http\Controllers\Auth\ChooseCompanyToLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompanyCampaignController;
use App\Http\Controllers\CustomCollectionsController;
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
use Maatwebsite\Excel\Facades\Excel;

Route::middleware(GuestMiddleware::class)->group(function () {
    Route::get('/', [PresentationController::class, 'index'])->name('apresentacao');

    Route::get('/cadastro/empresa', [RegisterController::class, 'showCompanyRegister'])->name('auth.cadastro.empresa');
    Route::post('/cadastro/empresa', [RegisterController::class, 'attemptCompanyRegister']);

    Route::view('/login/usuario-interno', 'auth.login.index')->name('auth.login.usuario-interno');
    Route::post('/login/usuario-interno', [LoginController::class, 'attemptInternalUserLogin']);

    Route::get('/login/gestor/{user}/senha', [LoginController::class, 'showPasswordForm'])->name('auth.login.gestor.senha');
    Route::post('/login/gestor/{user}/senha', [LoginController::class, 'checkManagerPassword'])->name('auth.login.gestor.verificar-senha');

    Route::view('/login/empresa', 'auth.login.company')->name('auth.login.empresa');
    Route::post('/login/empresa', [LoginController::class, 'attemptCompanyLogin']);

    Route::get('/login/usuario-interno/{user}/escolher-empresa', [LoginController::class, 'showChooseCompany'])->name('auth.login.usuario-interno.escolher-empresa');
    Route::post('/login/usuario-interno/{user}/escolher-empresa/{company?}', [LoginController::class, 'loginUserWithCompany'])->name('auth.login.usuario-interno.entrar-com-empresa');

});

Route::middleware(AuthMiddleware::class)->group(function () {
    Route::view('/bem-vindo/empresa', 'private.welcome.company')->name('welcome.company');
    Route::view('/bem-vindo/colaborador', 'private.welcome.user')->name('welcome.user');

    Route::get('/colecao/{collection}/teste/{test?}', TestsController::class)->name('responder-teste');
    Route::post('/colecao/{collection}/teste/{test}/enviar', [TestsController::class, 'handleTestSubmit'])->name('enviar-teste');

    Route::get('/comentario', [UserFeedbackController::class, 'create'])->name('feedbacks.create');
    Route::post('/comentario', [UserFeedbackController::class, 'store']);

    Route::get('/exportar-comentarios', [UserFeedbackController::class, 'export'])->name('export-feedbacks');

    Route::view('/obrigado', 'private.tests.thanks')->name('responder-teste.thanks');

    Route::get('/user/importar', [UserController::class, 'showImport'])->name('user.import');
    Route::post('/user/importar', [UserController::class, 'import']);

    Route::post('/users/check-cpf', [UserController::class, 'checkCpf'])->name('user.create.checkCpf');

    Route::get('/user/adicionar-usuario-existente', [UserController::class, 'showAddExistingUser'])->name('user.add-existing');
    Route::post('/user/adicionar-usuario-existente', [UserController::class, 'addExistingUser']);

    Route::view('/user/redefinir-senha', 'auth.login.reset-password')->name('auth.login.redefinir-senha');
    Route::post('/user/redefinir-senha', [UserController::class, 'resetUserPassword']);
    
    Route::put('/user/{user}/redefinir-senha', [UserController::class, 'resetPasswordOnShowScreen'])->name('user.reset-password');
    
    Route::put('/empresa/{company}/redefinir-senha', [CompanyController::class, 'resetCompanyPassword'])->name('company.reset-password');

    Route::resource('company', CompanyController::class);
    Route::resource('user', UserController::class);
    Route::resource('campaign', CompanyCampaignController::class);
    
    
    Route::post('/user/trocar-empresa', [LoginController::class, 'switchCompanyLogin'])->name('user.switch-company');

    Route::get('/user/{user}/permissoes', [UserController::class, 'showPermissions'])->name('user.permissions');
    Route::post('/user/{user}/permissoes', [UserController::class, 'updatePermissions']);

    Route::get('/user/{user}/visao-de-setores', [UserController::class, 'showDepartmentScope'])->name('user.department-scope');
    Route::post('/user/{user}/visao-de-setores', [UserController::class, 'updateDepartmentScopes']);

    Route::get('/indicadores', CompanyMetricsController::class)->name('company-metrics');
    Route::post('/indicadores', [CompanyMetricsController::class, 'storeMetrics']);

    Route::prefix('dashboard')->group(function () {
        Route::get('demografia', DemographicsMainController::class)->name('dashboard.demographics');

        Route::get('clima-organizacional', OrganizationalMainController::class)->name('dashboard.organizational-climate');
        Route::post('clima-organizacional', [OrganizationalMainController::class, 'createPDFReport'])->name('dashboard.organizational-climate.report');

        Route::get('clima-organizacional/respostas', OrganizationalAnswersController::class)->name('dashboard.organizational-climate.by-answers');
        Route::get('clima-organizacional/respostas/pdf', [OrganizationalAnswersController::class, 'createPDFReport'])->name('dashboard.organizational-climate.by-answers.report');

        Route::get('comentarios', [UserFeedbackController::class, 'index'])->name('feedbacks.index');
        Route::get('comentarios/{feedback}', [UserFeedbackController::class, 'show'])->name('feedbacks.show');

        Route::get('psicossocial', PsychosocialMainController::class)->name('dashboard.psychosocial');
        Route::get('psicossocial/riscos', PsychosocialRisksController::class)->name('dashboard.psychosocial.risks');
        Route::get('psicossocial/riscos/pdf', [PsychosocialRisksController::class, 'generatePDF'])->name('dashboard.psychosocial.risks.pdf');

        Route::get('psicossocial/{test}/departamentos', PsychosocialResultsByDepartmentController::class)->name('dashboard.psychosocial.by-department');
        Route::get('psicossocial/{test}/lista', PsychosocialResultsListController::class)->name('dashboard.psychosocial.list');
    });

    Route::get('empresa/colecoes-de-testes', [CustomCollectionsController::class, 'showCompanyCollections'])->name('company-collections');
    Route::get('empresa/colecoes-de-testes/{collection}/editar', [CustomCollectionsController::class, 'editCollection'])->name('company-collections.update');
    Route::post('empresa/colecoes-de-testes/{collection}/editar', [CustomCollectionsController::class, 'updateCollection']);

    Route::view('/politica-de-privacidade', 'private.lgpd.privacy-policy')->name('privacy-policy');

    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});
