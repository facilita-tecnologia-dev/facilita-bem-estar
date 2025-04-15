<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Helpers\Helper;
use App\Models\Company;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TestResultsListController
{
    protected $testService;

    protected $companyUserCollections;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
        // $this->helper = $helper;
        // $this->company = $this->helper->getCompanyUsers(hasCollection: true);
    }

    public function __invoke(Request $request, string $testName)
    {
        Gate::authorize('view-manager-screens');

        $queryStringName = $request->name;
        $queryStringDepartment = $request->department;
        $queryStringOccupation = $request->occupation;

        $this->companyUserCollections = $this->pageQuery($testName, $queryStringName, $queryStringDepartment, $queryStringOccupation);

        // $departmentsToFilter = $this->getDepartmentsToFilter();
        // $occupationsToFilter = $this->getOccupationsToFilter();

        $usersList = $this->getCompiledTestsData();

        return view('private.dashboard.test-results-list', compact(
            'testName',
            'usersList',
            // 'departmentsToFilter',
            // 'occupationsToFilter',
            'queryStringName',
            'queryStringDepartment',
            'queryStringOccupation'
        ));
    }

    private function pageQuery($testName, $queryStringName, $queryStringDepartment, $queryStringOccupation)
    {
        $companyUserCollections = Company::where('id', session('company')->id)
            ->with('metrics.metricType')
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
                            ->whereHas('tests', function ($q) use ($testName) {
                                $q->whereHas('testType', function ($subQuery) use ($testName) {
                                    $subQuery->where('display_name', $testName);
                                });
                            })
                            ->with('collectionType')
                            ->with('tests', function ($userTest) use ($testName) {
                                $userTest
                                    ->whereHas('testType', function ($q) use ($testName) {
                                        $q->where('display_name', $testName);
                                    })
                                    ->with(['answers', 'questions.options', 'testType.risks.relatedQuestions']);
                            })->limit(1);
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

            $answers = [];

            foreach ($userTest->answers as $answer) {
                $question = $userTest->questions->where('id', $answer->question_id)->first();
                $relatedOption = $question->options->where('id', $answer->question_option_id)->first();
                $answers[$question->id] = $relatedOption->value;
            }

            $evaluatedTest = $this->testService->evaluateTest($userTest, $answers, $this->companyUserCollections->metrics);

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
    // private function getDepartmentsToFilter(): array
    // {
    //     $departments = array_unique($this->company->users->pluck('department')->toArray());

    //     return $departments;
    // }

    // /**
    //  * Retorna um array com os cargos para filtrar.
    //  */
    // private function getOccupationsToFilter(): array
    // {
    //     $occupations = array_unique($this->company->users->pluck('occupation')->toArray());

    //     return $occupations;
    // }
}
