<?php

use App\Exports\CommentsExport;
use App\Http\Controllers\ActionPlanController;
use App\Http\Controllers\Auth\ChooseCompanyToLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompanyCampaignController;
use App\Http\Controllers\CustomCollectionController;
use App\Http\Controllers\CustomCollectionsController;
use App\Http\Controllers\CustomControlActionController;
use App\Http\Controllers\CustomQuestionController;
use App\Http\Controllers\CustomTestController;
use App\Http\Controllers\ForgotPasswordController;
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
use App\Http\Controllers\Private\WelcomeController;
use App\Http\Controllers\Public\PresentationController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\GuestMiddleware;
use App\Mail\CampaignEmail;
use App\Models\CustomControlAction;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::middleware(GuestMiddleware::class)->group(function () {
    Route::get('/', [PresentationController::class, 'index'])->name('apresentacao');

    Route::get('/cadastro/empresa', [RegisterController::class, 'showCompanyRegister'])->name('auth.cadastro.empresa');
    Route::post('/cadastro/empresa', [RegisterController::class, 'attemptCompanyRegister']);

    Route::prefix('login')->group(function(){
        Route::prefix('usuario-interno')->group(function(){
            Route::view('/', 'auth.login.user.index')->name('auth.login.usuario-interno');
            Route::post('/', [LoginController::class, 'attemptInternalUserLogin']);
        
            Route::get('esqueci-a-senha', [UserController::class, 'showForgotPassword'])->name('user.password.request');
            Route::post('esqueci-a-senha', [UserController::class, 'sendResetEmail'])->name('user.password.email');
            
            Route::get('redefinir-senha/{token}', [UserController::class, 'showResetPassword'])->name('user.password.reset');
            Route::post('redefinir-senha', [UserController::class, 'resetPassword'])->name('user.password.update');
        
            Route::get('{user}/senha', [LoginController::class, 'showPasswordForm'])->name('auth.login.usuario-interno.senha');
            Route::post('{user}/senha', [LoginController::class, 'checkManagerPassword'])->name('auth.login.usuario-interno.verificar-senha');
            
            Route::get('{user}/escolher-empresa', [LoginController::class, 'showChooseCompany'])->name('auth.login.usuario-interno.escolher-empresa');
            Route::post('{user}/escolher-empresa/{company?}', [LoginController::class, 'loginUserWithCompany'])->name('auth.login.usuario-interno.entrar-com-empresa');
        });
    
        Route::prefix('empresa')->group(function(){
            Route::view('/', 'auth.login.company.index')->name('auth.login.empresa');
            Route::post('/', [LoginController::class, 'attemptCompanyLogin']);
    
            Route::get('esqueci-a-senha', [CompanyController::class, 'showForgotPassword'])->name('company.password.request');
            Route::post('esqueci-a-senha', [CompanyController::class, 'sendResetEmail'])->name('company.password.email');
            
            Route::get('redefinir-senha/{token}', [CompanyController::class, 'showResetPassword'])->name('company.password.reset');
            Route::post('redefinir-senha', [CompanyController::class, 'resetPassword'])->name('company.password.update');
        });
    });

});

Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get('/bem-vindo/empresa', [WelcomeController::class, 'welcomeCompany'])->name('welcome.company');
    Route::get('/bem-vindo/colaborador', [WelcomeController::class, 'welcomeUser'])->name('welcome.user');

    Route::get('/colecao/{collection}/teste/{test?}', TestsController::class)->name('responder-teste');
    Route::post('/colecao/{collection}/teste/{test}/enviar', [TestsController::class, 'handleTestSubmit'])->name('enviar-teste');

    Route::get('/comentarios/criar', [UserFeedbackController::class, 'create'])->name('feedbacks.create');
    Route::post('/comentarios/criar', [UserFeedbackController::class, 'store']);
    Route::get('/comentarios/exportar', [UserFeedbackController::class, 'export'])->name('export-feedbacks');

    Route::view('/obrigado', 'private.tests.thanks')->name('responder-teste.thanks');

    Route::get('/user/importar', [UserController::class, 'showImport'])->name('user.import');
    Route::post('/user/importar', [UserController::class, 'import']);
    Route::post('/users/check-cpf', [UserController::class, 'checkCpf'])->name('user.create.checkCpf');
    // Route::view('/user/redefinir-senha', 'auth.login.reset-password')->name('auth.login.redefinir-senha');
    // Route::post('/user/redefinir-senha', [UserController::class, 'resetUserPassword']);
    Route::put('/user/{user}/redefinir-senha', [UserController::class, 'resetPasswordOnShowScreen'])->name('user.reset-password');
    
    Route::put('/empresa/{company}/redefinir-senha', [CompanyController::class, 'resetCompanyPassword'])->name('company.reset-password');

    Route::resource('company', CompanyController::class);
    Route::resource('user', UserController::class);


    Route::prefix('/campanhas')->group(function(){
        Route::get('/', [CompanyCampaignController::class, 'index'])->name('campaign.index');
        Route::get('criar', [CompanyCampaignController::class, 'create'])->name('campaign.create');
        Route::post('criar', [CompanyCampaignController::class, 'store'])->name('campaign.store');
        Route::get('{campaign}', [CompanyCampaignController::class, 'show'])->name('campaign.show');
        Route::post('{campaign}/notificar', [CompanyCampaignController::class, 'dispatchNotifications'])->name('campaign.dispatch-emails');
        Route::get('{campaign}/editar', [CompanyCampaignController::class, 'edit'])->name('campaign.edit');
        Route::put('{campaign}/editar', [CompanyCampaignController::class, 'update'])->name('campaign.update');
        Route::put('{campaign}/excluir', [CompanyCampaignController::class, 'destroy'])->name('campaign.destroy');
    });

    // Route::resource('campaign', CompanyCampaignController::class);
    
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

    Route::prefix('empresa/colecoes-de-testes')->group(function(){
        Route::get('/', [CustomCollectionController::class, 'index'])->name('custom-collections.index');
        Route::get('criar', [CustomCollectionController::class, 'create'])->name('custom-collections.create');
        Route::post('criar', [CustomCollectionController::class, 'store'])->name('custom-collections.store');
        Route::get('{customCollection}', [CustomCollectionController::class, 'show'])->name('custom-collections.show');
        Route::put('{customCollection}/atualizar', [CustomCollectionController::class, 'update'])->name('custom-collections.update');
        Route::delete('{customCollection}/excluir', [CustomCollectionController::class, 'destroy'])->name('custom-collections.destroy');
        
        Route::post('{customCollection}/testes/criar', [CustomTestController::class, 'store'])->name('custom-collections.tests.store');
        Route::get('{customCollection}/testes/{customTest}/excluir', [CustomTestController::class, 'destroy'])->name('custom-collections.tests.destroy');    
        
        Route::post('{customCollection}/questoes/criar', [CustomQuestionController::class, 'store'])->name('custom-collections.tests.questions.store');
        Route::get('{customCollection}/questoes/{customQuestion}/excluir', [CustomQuestionController::class, 'destroy'])->name('custom-collections.tests.questions.destroy');
    });

    Route::prefix('empresa/planos-de-acao')->group(function(){
        Route::post('{actionPlan}/risco/{risk}/medidas-de-controle/criar', [CustomControlActionController::class, 'store'])->name('action-plan.risk.control-action.store');
        Route::get('{actionPlan}/risco/{risk}/medidas-de-controle/{controlAction}/delete', [CustomControlActionController::class, 'destroy'])->name('action-plan.risk.control-action.destroy');
    
        Route::get('{actionPlan}', [ActionPlanController::class, 'show'])->name('action-plan.show');
        Route::get('{actionPlan}/risco/{risk}/editar', [ActionPlanController::class, 'edit'])->name('action-plan.risk.edit');
        Route::put('{actionPlan}/risco/{risk}/atualizar', [ActionPlanController::class, 'update'])->name('action-plan.risk.update');
    });

    Route::view('/politica-de-privacidade', 'private.lgpd.privacy-policy')->name('privacy-policy');

    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});
