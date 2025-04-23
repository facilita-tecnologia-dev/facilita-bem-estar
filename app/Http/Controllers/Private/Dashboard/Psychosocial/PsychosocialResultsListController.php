<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Models\Company;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PsychosocialResultsListController
{
    protected $testService;

    protected $pageData;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function __invoke(Request $request, string $testName)
    {
        Gate::authorize('view-manager-screens');

        $this->pageData = $this->pageQuery(
            $testName,
            $request->name,
            $request->cpf,
            $request->department,
            $request->occupation,
            $request->gender,
        );


        $usersList = $this->getCompiledPageData();

        $filtersApplied = array_filter($request->query(), fn($queryParam) => $queryParam != null);

        return view('private.dashboard.psychosocial.test-results-list', [
            'testName' => $testName,
            'usersList' => $usersList,
            'filtersApplied' => $filtersApplied,
            'filteredUsers' => count($this->pageData) > 0 ? $this->pageData : null,
        ]);
    }

    private function pageQuery($testName, $filteredName = null, $filteredCPF = null, $filteredDepartment = null, $filteredOccupation = null, $filteredGender = null)
    {
        $pageData = session('company')
            ->users()
            ->has('collections')
            ->hasAttribute('name', 'like', "%$filteredName%")
            ->hasAttribute('cpf', 'like', "%$filteredCPF%")
            ->hasAttribute('gender', '=', $filteredGender)
            ->hasAttribute('department', '=', $filteredDepartment)
            ->hasAttribute('occupation', '=', $filteredOccupation)
            ->select('users.id', 'users.name', 'users.department', 'users.occupation', 'users.gender')
            ->withLatestPsychosocialCollection(only: $testName)
            ->get();

        return $pageData;
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

            if (! isset($testCompiled[$user->name]['severity'])) {
                $testCompiled[$user->name]['severity']['severity_title'] = $evaluatedTest['severity_title'];
                $testCompiled[$user->name]['severity']['severity_color'] = $evaluatedTest['severity_color'];
                $testCompiled[$user->name]['severity']['severity_key'] = $evaluatedTest['severity_key'];
            }
        }

        ksort($testCompiled);

        return $testCompiled;
    }
}
