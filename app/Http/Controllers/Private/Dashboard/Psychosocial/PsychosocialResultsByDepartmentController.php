<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

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

    public function __invoke(Request $request, $testName)
    {
        Gate::authorize('psychosocial-dashboard-view');
        $this->pageData = $this->query($request, $testName);

        $resultsPerDepartment = $this->getCompiledPageData();

        return view('private.dashboard.psychosocial.by-department', compact(
            'testName',
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

            $customTests = $psychosocialCollection
                ->tests()
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
                    ->with(['parentCollection.userOwner']);
                })
            ->get();

            $testTypes = Test::where('collection_id', 1)
                ->withRisks(function($query) use($request) {
                    $query->withRelatedQuestions(function($query) use($request) {
                        $query
                        ->withParentQuestionStatement()
                        ->withParentQuestionInverted()
                        ->withAnswerAverage($request);
                    });
                })
            ->get();
            
            foreach($testTypes as $test){
                $relatedTest = $customTests->firstWhere('key_name', $test['key_name']);
                $test->userTests = $relatedTest['userTests'];
            }
        }

        return $testTypes ?? [];
    }

    private function getCompiledPageData()
    {
        $testCompiled = [];
        
        foreach($this->pageData as $testType){
            $this->compileTests($testType, $testCompiled);
        }

        return $testCompiled;
    }

    private function compileTests($testType, &$testCompiled)
    {
        $testType->average = round($testType['userTests']->avg('average_value'), 2);

        foreach($testType['userTests'] as $userTest){
            $userDepartment = $userTest['parentCollection']['userOwner']['department'];
            $this->sumDepartmentCount($userDepartment, $testCompiled);

            $evaluatedTest = $this->testService->evaluateTest($testType, $userTest, session('company')->metrics);

            $this->updateTestSeverities($userDepartment, $evaluatedTest, $testCompiled);
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

    private function updateTestSeverities(string $userDepartment, array $evaluatedTest, array &$testCompiled)
    {
        if (! isset($testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']])) {
            $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['count'] = 0;
            $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['severity_color'] = $evaluatedTest['severity_color'];
            $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['severity_key'] = $evaluatedTest['severity_key'];
        }

        $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['count']++;
    }

    private function sortSeveritiesByDepartment(string $userDepartment, array &$testCompiled)
    {
        uasort($testCompiled[$userDepartment]['severities'], function ($a, $b) {
            return $b['severity_key'] <=> $a['severity_key'];
        });
    }
}
