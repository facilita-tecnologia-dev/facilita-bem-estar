<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

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

    public function __invoke(Request $request, string $testName)
    {
        Gate::authorize('psychosocial-dashboard-view');

        $this->pageData = $this->query($request, $testName);

        $usersList = $this->getCompiledPageData();

        return view('private.dashboard.psychosocial.list', [
            'testName' => $testName,
            'usersList' => $usersList,
            'pageData' => $this->pageData,
            'filtersApplied' => array_filter($request->query(), fn ($queryParam) => $queryParam != null),
            'filteredUserCount' => $this->pageData->firstWhere('display_name', $testName)['userTests']->count(),
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
            $user = $userTest['parentCollection']['userOwner'];

            $evaluatedTest = $this->testService->evaluateTest($testType, $userTest, session('company')->metrics);
            $testCompiled[$user->name]['user'] = $user;
            $this->compileTestResults($user, $evaluatedTest, $testCompiled);
        }
    }

    private function compileTestResults(User $user, array $evaluatedTest, array &$testCompiled)
    {
        if (! isset($testCompiled[$user->name]['severity'])) {
            $testCompiled[$user->name]['severity']['severity_title'] = $evaluatedTest['severity_title'];
            $testCompiled[$user->name]['severity']['severity_color'] = $evaluatedTest['severity_color'];
            $testCompiled[$user->name]['severity']['severity_key'] = $evaluatedTest['severity_key'];
        }
    }
}
