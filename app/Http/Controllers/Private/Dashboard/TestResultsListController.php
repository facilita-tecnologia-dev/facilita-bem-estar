<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Models\Company;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class TestResultsListController
{
    protected $testService;

    protected $companyUserCollections;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function __invoke(Request $request, string $testName)
    {
        Gate::authorize('view-manager-screens');

        $queryStringName = $request->name;
        $queryStringDepartment = $request->department;
        $queryStringOccupation = $request->occupation;

        $this->companyUserCollections = $this->pageQuery($testName, $queryStringName, $queryStringDepartment, $queryStringOccupation);

        $departmentsToFilter = $this->getDepartmentsToFilter();
        $occupationsToFilter = $this->getOccupationsToFilter();

        $usersList = $this->getCompiledTestsData();

        return view('private.dashboard.test-results-list', compact(
            'testName',
            'usersList',
            'departmentsToFilter',
            'occupationsToFilter',
            'queryStringName',
            'queryStringDepartment',
            'queryStringOccupation'
        ));
    }

    private function pageQuery($testName, $queryStringName, $queryStringDepartment, $queryStringOccupation)
    {
        $companyUserCollections = Company::where('id', session('company')->id)
            ->with('metrics')
            ->with('users', function ($user) use ($testName, $queryStringName, $queryStringDepartment, $queryStringOccupation) {
                $user
                    ->has('collections')
                    ->when($queryStringName, function ($query) use ($queryStringName) {
                        $query->where('name', 'like', "%$queryStringName%");
                    })
                    ->when($queryStringDepartment, function ($query) use ($queryStringDepartment) {
                        $query->where('department', '=', "$queryStringDepartment");
                    })
                    ->when($queryStringOccupation, function ($query) use ($queryStringOccupation) {
                        $query->where('occupation', '=', "$queryStringOccupation");
                    })
                    ->with('latestCollections', function ($latestCollection) use ($testName) {
                        $latestCollection
                            ->whereHas('tests', function ($test) use ($testName) {
                                $test->whereHas('testType', function ($testType) use ($testName) {
                                    $testType->where('display_name', $testName);
                                });
                            })
                            ->with('tests')->limit(1);
                    });
            })
            ->first();

        return $companyUserCollections;
    }

    /**
     * Retorna um objeto Company com os usuarios e seus testes.
     */
    private function getCompiledTestsData()
    {
        $testCompiled = [];

        foreach ($this->companyUserCollections->users as $user) {
            $userTest = $user->latestCollections[0]->tests[0];

            $testCompiled[$user->name]['user'] = $user;

            $evaluatedTest = $this->testService->evaluateTest($userTest, $this->companyUserCollections->metrics);

            if (! isset($testCompiled[$user->name]['severity'])) {
                $testCompiled[$user->name]['severity']['severity_title'] = $evaluatedTest['severity_title'];
                $testCompiled[$user->name]['severity']['severity_color'] = $evaluatedTest['severity_color'];
            }
        }

        return $testCompiled;
    }

    /**
     * Retorna um array com os setores para filtrar.
     */
    private function getDepartmentsToFilter(): Collection
    {
        $departments = session('company')->users()->whereNotNull('department')->distinct()->pluck('department');

        return $departments;
    }

    /**
     * Retorna um array com os cargos para filtrar.
     */
    private function getOccupationsToFilter(): Collection
    {
        $occupations = session('company')->users()->whereNotNull('occupation')->distinct()->pluck('occupation');

        return $occupations;
    }
}
