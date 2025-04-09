<?php

namespace App\Http\Controllers\Private;

use App\Models\PendingTestAnswer;
use App\Models\Risk;
use App\Models\Test;
use App\Models\User;
use App\Models\UserTest;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TestsController
{
    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function showChooseScreen()
    {
        // $userLatestCollection = User::where('id', auth()->user()->id)->with('collections')->first();

        return view('private.tests.choose-test', [
            'hasCollection' => false,
        ]);
    }

    public function __invoke($testIndex = 1)
    {
        // if (Gate::denies('answer-test')) {
        //     abort(403, 'Acesso não autorizado');
        // }

        $test = Test::query()
            ->where('order', '=', $testIndex)
            ->with('questions', function ($query) {
                $query->inRandomOrder()->with('options', function ($q) {
                    $q->orderBy('value');
                });
            }
            )
            ->firstOrFail();

        $pendingAnswers = PendingTestAnswer::query()->where('user_id', '=', Auth::user()->id)->where('test_id', '=', $test->id)->get();

        return view('private.tests.test', [
            'test' => $test,
            'testIndex' => $testIndex,
            'pendingAnswers' => $pendingAnswers ?? [],
        ]);
    }

    public function handleTestSubmit(Request $request, $testIndex)
    {
        $testInfo = Test::query()
            ->where('order', '=', $testIndex)
            ->with('questions.options')
            ->first();

        $validationRules = $this->generateValidationRules($testInfo);
        $validatedData = $request->validate($validationRules);

        $processedTest = $this->testService->processTest($validatedData, $testInfo);

        $totalTests = Test::max('order');
        if ($testIndex == $totalTests) {
            $testResults = $this->getTestResultsFromSession();
            $storedResults = $this->storeResultsOnDatabase($testResults);

            if (! $storedResults) {
                return back();
            }

            $request->session()->forget(array_keys($testResults));

            return to_route('choose-test');
        }

        return to_route('test', $testIndex + 1);
    }

    private function generateValidationRules($testInfo): array
    {
        $validationRules = [];

        $testQuestions = $testInfo->questions->groupBy('id');

        foreach ($testQuestions as $question) {
            $validationRules[$question[0]->id] = 'required';
        }

        return $validationRules;
    }

    private function getTestResultsFromSession(): array
    {
        $allTestResults = collect(session()->all())
            ->filter(function ($_, $key) {
                return str_ends_with($key, '-result');
            })
            ->toArray();

        return $allTestResults;
    }

    private function storeResultsOnDatabase($testResults): array
    {
        PendingTestAnswer::query()->where('user_id', '=', Auth::user()->id)->delete();

        DB::transaction(function () use ($testResults) {
            $newTestCollectionId = DB::table('user_collections')->insertGetId([
                'user_id' => Auth::user()->id,
                'collection_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $tests = Test::with('questions.options')->get();

            foreach ($testResults as $key => $testResult) {
                $testKeyName = str_replace('-result', '', $key);

                $testType = $tests->where('key_name', $testKeyName)->firstOrFail();
                $userTest = UserTest::create([
                    'user_collection_id' => $newTestCollectionId,
                    'test_id' => $testType->id,
                    'score' => $testResult['score'],
                    'severity_title' => $testResult['severity_title'],
                    'severity_color' => $testResult['severity_color'],
                ]);

                foreach ($testResult['answers'] as $questionId => $answer) {
                    $question = $testType->questions->where('id', $questionId)->first();
                    $option = $question->options->where('value', $answer)->first();

                    DB::table('user_answers')->insert([
                        'question_option_id' => $option->id,
                        'question_id' => $questionId,
                        'user_test_id' => $userTest->id,
                    ]);
                }

                $riskNames = array_keys($testResult['risks']);
                $risks = Risk::whereIn('name', $riskNames)
                    ->select('id', 'name')
                    ->get()
                    ->keyBy('name');

                foreach ($testResult['risks'] as $key => $risk) {
                    DB::table('user_risk_results')->insert([
                        'user_collection_id' => $newTestCollectionId,
                        'risk_id' => $risks[$key]->id,
                        'score' => $risk['evaluatedRisk'],
                        'points' => $risk['riskPoints'],
                    ]);
                }

            }

        });

        return $testResults;
    }
}
