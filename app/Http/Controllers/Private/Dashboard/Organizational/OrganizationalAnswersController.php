<?php

namespace App\Http\Controllers\Private\Dashboard\Organizational;

use App\Helpers\AuthGuardHelper;
use App\Models\User;
use App\Services\Dashboard\OrganizationalClimateService;
use App\Services\TestService;
use App\Services\User\UserFilterService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrganizationalAnswersController
{
    protected $testService;

    protected $filterService;

    protected $pageData;
    
    protected $generalTestResults;

    protected $scopedTestResults;

    protected $organizationalClimateService;

    public function __construct(TestService $testService, UserFilterService $filterService, OrganizationalClimateService $organizationalClimateService)
    {
        $this->testService = $testService;
        $this->filterService = $filterService;
        $this->organizationalClimateService = $organizationalClimateService;
    }

    public function __invoke(Request $request)
    {
        Gate::authorize('organizational-dashboard-view');
        $this->pageData = $this->query(request('test'));

        $this->generalTestResults = $this->organizationalClimateService->getOrganizationalData($request, true);

        $organizationalClimateResults = $this->getCompiledPageData($request);

        return view('private.dashboard.organizational.by-answers', compact(
            'organizationalClimateResults',
        ));
    }

    public function createPDFReport(Request $request)
    {
        Gate::authorize('organizational-dashboard-view');

        $company = session('company');

        $this->pageData = $this->query(request('test'));

        $organizationalClimateResults = $this->getCompiledPageData($request);
        $companyLogo = $company->logo;
        $companyName = $company->name;

        $pdf = Pdf::loadView('pdf.organizational-climate-answers', [
            'companyLogo' => $companyLogo,
            'companyName' => $companyName,
            'organizationalClimateResults' => $organizationalClimateResults,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('graficos_clima_organizacional.pdf');
    }

    private function query($justTest = null)
    {
        $companyUserIds = session('company')->users->pluck('id');        
        $query = User::whereIn('id', $companyUserIds);

        return $this->filterService->apply($query)
            ->select('id', 'department')
            ->whereHas('latestOrganizationalClimateCollection')
            ->with(['latestOrganizationalClimateCollection' => function($query) use($justTest) {
                $query->with(['collectionType.tests.questions', 'tests' => function($q) use($justTest) {
                    $q->when($justTest, function($query) use($justTest) {
                        $query->whereHas('testType', function($q) use($justTest) {
                            $q->where('display_name', $justTest);
                        });
                    })
                    ->with('answers', 'testType');
                }]);
            }])
            ->get();
    }

    private function getCompiledPageData()
    {   
        $testCompiled = [];

        $isUser = AuthGuardHelper::user() instanceof User;
        $userDepartmentScopes = $isUser ? AuthGuardHelper::user()->departmentScopes->where('allowed', 1) : false;
        
        foreach($this->pageData as $user){
            if ($user->latestOrganizationalClimateCollection && (!$isUser || $userDepartmentScopes->where('department', $user->department)->count())) {
                $this->compileUserTests($user, $testCompiled);
            }
        }

        $this->calculateAverageScoreByQuestion($testCompiled);
        
        krsort($testCompiled);

        return $testCompiled;
    }

    private function compileUserTests(User $user, &$testCompiled)
    {
        $tests = $user['latestOrganizationalClimateCollection']['tests'];

        $tests->each(function($userTest) use($user, &$testCompiled) {
            $testDisplayName = $userTest['testType']['display_name'];
            
            $evaluatedTest = $this->testService->evaluateIndividualTest($userTest['testType'], $userTest, session('company')->metrics, 'organizational-climate');
            $questions = $userTest->answers->map(fn($answer) => $answer->parentQuestion);

            $this->updateAnswers($user, $testDisplayName, $evaluatedTest, $questions->keyBy('id'), $testCompiled);
        }); 
    }

    private function updateAnswers(User $user, string $testDisplayName, array $evaluatedTest, $questions, &$testCompiled)
    {
        foreach ($evaluatedTest['processed_answers'] as $questionNumber => $answer) {
            $testCompiled[$testDisplayName][$questions[$questionNumber]->statement]['Geral']['answers'][] = $answer;
            $testCompiled[$testDisplayName][$questions[$questionNumber]->statement][$user->department]['answers'][] = $answer;
        }
    }

    private function calculateAverageScoreByQuestion(&$testCompiled)
    {
        foreach ($testCompiled as $testName => $test) {
            foreach ($test as $questionStatement => $question) {
                foreach ($question as $departmentName => $departmentAnswers) {
                    $count = count($departmentAnswers['answers']);
                    $sum = array_sum($departmentAnswers['answers']);
                    $testCompiled[$testName][$questionStatement][$departmentName]['total_average'] = $sum / $count;
                    unset($testCompiled[$testName][$questionStatement][$departmentName]['answers']);
                }
            }
        }
    }
}
