<?php

namespace App\Http\Controllers\Private\Dashboard\Organizational;

use App\Models\User;
use App\Services\TestService;
use App\Services\User\UserFilterService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrganizationalAnswersController
{
    protected $testService;

    protected $filterService;
    
    protected $pageData;

    public function __construct(TestService $testService, UserFilterService $filterService)
    {
        $this->testService = $testService;
        $this->filterService = $filterService;
    }

    public function __invoke(Request $request)
    {
        $this->query($request);

        $organizationalClimateResults = $this->getCompiledPageData();

        return view('private.dashboard.organizational.by-answers', compact(
            'organizationalClimateResults',
        ));
    }

    private function query($request){
        // Catching users
        $query = session('company')->users()
            ->whereHas('latestOrganizationalClimateCollection', function ($query) {
                $query->whereYear('created_at', Carbon::now()->year);
            })
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
        ->getQuery();

        // Catching user tests
        $this->pageData = $query
            ->withLatestOrganizationalClimateCollection(function ($query) use ($request) {
                $query->whereYear('created_at', $request->year ?? '2025')
                    ->withCollectionTypeName('organizational-climate')
                    ->withTests(function ($query) use($request) {
                        $query
                            ->when($request->test, fn($q) => $q->justOneTest($request->test))
                            ->withAnswers()
                            ->withTestType();
                    });
            })
        ->get();
    }

    public function createPDFReport(Request $request){
        $company = session('company');
        $this->query($request);

        $organizationalClimateResults = $this->getCompiledPageData();
        $companyLogo = $company->logo;
        $companyName = $company->name;

        $pdf = Pdf::loadView('pdf.organizational-climate-answers', [
            'companyLogo' => $companyLogo,
            'companyName' => $companyName,
            'organizationalClimateResults' => $organizationalClimateResults
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('graficos_clima_organizacional.pdf');
    }

    private function getCompiledPageData()
    {
        $metrics = session('company')->metrics;

        $testCompiled = [];

        foreach ($this->pageData as $user) {
            if ($user->latestOrganizationalClimateCollection) {
                $this->compileUserResults($user, $metrics, $testCompiled);
            }
        }

        $this->calculateAverageScoreByQuestion($testCompiled);

        krsort($testCompiled);

        return $testCompiled;
    }

    private function compileUserResults(User $user, Collection $metrics, &$testCompiled)
    {
        foreach ($user['latestOrganizationalClimateCollection']['tests'] as $userTest) {
            $testDisplayName = $userTest->testType->display_name;

            $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics);

            $questions = $userTest->testType->questions->keyBy('id');

            $this->updateAnswers($user, $testDisplayName, $evaluatedTest, $questions, $testCompiled);
        }
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
