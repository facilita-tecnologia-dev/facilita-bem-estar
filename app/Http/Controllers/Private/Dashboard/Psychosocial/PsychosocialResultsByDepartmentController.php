<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Enums\RiskLevelEnum;
use App\Enums\RiskSeverityEnum;
use App\Models\Test;
use App\Services\TestService;
use App\Services\User\UserFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PsychosocialResultsByDepartmentController
{
    protected $testService;

    protected $pageData;

    protected $filterService;

    public function __construct(TestService $testService, UserFilterService $filterService)
    {
        $this->testService = $testService;
        $this->filterService = $filterService;
    }

    public function __invoke(Request $request, $testName, $riskName)
    {
        Gate::authorize('psychosocial-dashboard-view');
        
        $this->pageData = $this->query($request, $testName);

        $resultsPerDepartment = $this->getCompiledPageData($riskName);

        return view('private.dashboard.psychosocial.by-department', compact(
            'testName',
            'riskName',
            'resultsPerDepartment',
        ));
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
            $userDepartment = $userTest['parentCollection']['userOwner']['department'];
            $this->sumDepartmentCount($userDepartment, $testCompiled);

            $evaluatedTest = $this->testService->evaluateIndividualTest($testType, $userTest, session('company')->metrics);

            $this->updateTestSeverities($userDepartment, $evaluatedTest, $riskName, $testCompiled);
            $this->sortSeveritiesByDepartment($userDepartment, $testCompiled);
        }
    }
    
    private function sumDepartmentCount(string $userDepartment, array &$testCompiled)
    {
        if (! isset($testCompiled[$userDepartment]['total'])) {
            $testCompiled[$userDepartment]['total'] = 0;
        }

        $testCompiled[$userDepartment]['total']++;
    }

    private function updateTestSeverities(string $userDepartment, array $evaluatedTest, string $riskName, array &$testCompiled)
    {
        $riskLevel = RiskLevelEnum::labelFromValue($evaluatedTest['risks'][$riskName]['riskLevel']);
        
        if (! isset($testCompiled[$userDepartment]['severities'][$riskLevel])) {
            $testCompiled[$userDepartment]['severities'][$riskLevel]['count'] = 0;
            $testCompiled[$userDepartment]['severities'][$riskLevel]['severity_key'] = $evaluatedTest['risks'][$riskName]['riskLevel'];
            $testCompiled[$userDepartment]['severities'][$riskLevel]['severity_name'] = $riskLevel;
        }

        $testCompiled[$userDepartment]['severities'][$riskLevel]['count']++;
    }

    private function sortSeveritiesByDepartment(string $userDepartment, array &$testCompiled)
    {
        uasort($testCompiled[$userDepartment]['severities'], function ($a, $b) {
            return $b['severity_key'] <=> $a['severity_key'];
        });
    }
}
