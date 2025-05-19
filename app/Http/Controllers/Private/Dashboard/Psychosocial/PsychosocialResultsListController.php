<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Models\Company;
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
        dd($this->pageData);
        $usersList = $this->getCompiledPageData();
        $filteredUserCount = $this->pageData->total();
        $filtersApplied = array_filter($request->query(), fn ($queryParam) => $queryParam != null);

        return view('private.dashboard.psychosocial.list', [
            'testName' => $testName,
            'usersList' => $usersList,
            'pageData' => $this->pageData,
            'filtersApplied' => $filtersApplied,
            'filteredUserCount' => $filteredUserCount ? $filteredUserCount : null,
        ]);
    }

    private function query(Request $request, string $testName)
    {
        $query = session('company')->users()
            ->getQuery();

        return $this->filterService->sort($this->filterService->apply($query))
            ->whereHas('latestPsychosocialCollection', function ($query) {
                $query->whereYear('created_at', Carbon::now()->year);
            })
            ->withLatestPsychosocialCollection(function ($query) use ($request, $testName) {
                $query->whereYear('created_at', $request->year ?? '2025')
                    ->withCollectionTypeName('psychosocial-risks')
                    ->withTests(function ($query) use ($testName) {
                        $query
                            ->justOneTest($testName)
                            ->withAnswersSum()
                            ->withAnswersCount()
                            ->withTestType(function ($q) {
                                $q->withRisks(function ($i) {
                                    $i->withRelatedQuestions()
                                        ->withControlActions();
                                });
                            });
                    });
            })
            ->paginate(15)->appends(request()->query());
    }

    /**
     * Retorna um objeto Company com os usuarios e seus testes.
     */
    private function getCompiledPageData()
    {
        $metrics = session('company')->metrics;

        $testCompiled = [];

        foreach ($this->pageData as $user) {
            $userTest = $user->latestPsychosocialCollection->tests[0];
            $testCompiled[$user->name]['user'] = $user;

            $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics);

            $this->compileTestResults($user, $evaluatedTest, $testCompiled);
        }

        return $testCompiled;
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
