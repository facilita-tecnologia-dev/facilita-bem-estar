<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Enums\RiskLevelEnum;
use App\Models\Company;
use App\Models\Test;
use App\Models\User;
use App\Services\TestService;
use App\Services\User\UserFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PsychosocialResultsListController
{
    protected $testService;

    protected $filterService;

    protected $pageData;

    public function __construct(TestService $testService, UserFilterService $filterService)
    {
        $this->testService = $testService;
        $this->filterService = $filterService;
    }

    public function __invoke(Request $request, string $testName, string $riskName)
    {
        Gate::authorize('psychosocial-dashboard-view');

        $this->pageData = $this->query($request, $testName);

        $usersList = $this->getCompiledPageData($riskName);
        
        return view('private.dashboard.psychosocial.list', [
            'testName' => $testName,
            'riskName' => $riskName,
            'usersList' => $usersList,
            'pageData' => $this->pageData,
            'filtersApplied' => array_filter($request->query(), fn ($queryParam) => $queryParam != null),
            'filteredUserCount' => $this->pageData['userTests']->count(),
        ]);
    }
    
    private function query(Request $request, string $testName)
    { 
        $psychosocialCampaign = session('company')->campaigns()
            ->whereYear('end_date', now()->year)
            ->whereHas('collection', function($query){
                $query->where('collection_id', 1);
            })
            ->first();

        if($psychosocialCampaign){
            $psychosocialCollection = $psychosocialCampaign['collection'];

            $customTest = $psychosocialCollection
                ->tests()
                ->where('display_name', $testName)
                ->with('userTests', function($query) use($request, $testName) {
                    $query
                    ->justOneTest($testName)
                    ->whereYear('created_at', $request->year ?? Carbon::now()->year)
                    ->whereHas('parentCollection', function ($query) {
                        $query
                        ->where('company_id', session('company')->id)
                        ->whereHas('userOwner', function ($query) {
                            $this->filterService->apply($query);
                        });
                    })
                    ->withAvg(['answers as average_value'], 'value')
                    ->withAnswers()
                    ->with(['parentCollection.userOwner']);
                })
            ->first();

            $testType = Test::where('collection_id', 1)
                ->where('display_name', $testName)
                ->withRisks(function($query) use($request) {
                    $query->withRelatedQuestions(function($query) use($request) {
                        $query
                        ->withParentQuestionStatement()
                        ->withParentQuestionInverted()
                        ->withAnswerAverage($request);
                    });
                })
            ->first();
        
            $testType->userTests = $customTest['userTests'];
        }

        return $testType ?? [];
    }

    private function getCompiledPageData($riskName)
    {
        $testCompiled = [];
        
        $this->compileTests($this->pageData, $riskName, $testCompiled);

        return $testCompiled;
    }

    private function compileTests($testType, $riskName, &$testCompiled)
    {
        foreach($testType['userTests'] as $userTest){
            $user = $userTest['parentCollection']['userOwner'];

            $evaluatedTest = $this->testService->evaluateIndividualTest($testType, $userTest, session('company')->metrics);

            $testCompiled[$user->name]['user'] = $user;

            $this->compileTestResults($user, $evaluatedTest, $riskName, $testCompiled);
        }           
    }

    private function compileTestResults(User $user, array $evaluatedTest, string $riskName, array &$testCompiled)
    {
        $riskLevel = RiskLevelEnum::labelFromValue($evaluatedTest['risks'][$riskName]['riskLevel']);
        
        if (! isset($testCompiled[$user->name]['severity'])) {
            $testCompiled[$user->name]['severity']['severity_name'] = $riskLevel;
            $testCompiled[$user->name]['severity']['severity_key'] = $evaluatedTest['risks'][$riskName]['riskLevel'];
        }
    }
}
