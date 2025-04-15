<?php

namespace App\Http\Controllers\Private;

use App\Models\Collection;
use App\Models\PendingTestAnswer;
use App\Models\Test;
use App\Models\UserCollection;
use App\Models\UserTest;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestsController
{
    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function showChoose()
    {
        return view('private.tests.choose-test');
    }

    public function __invoke($testIndex = 1)
    {
        $test = Test::query()
            ->where('order', '=', $testIndex)
            ->with('questions', function ($query) {
                $query->inRandomOrder()->with('options', function ($q) {
                    $q->orderBy('value');
                });
            }
            )
            ->firstOrFail();

        $pendingAnswers = PendingTestAnswer::query()->where('user_id', '=', Auth::user()->id)->where('test_id', '=', $test->id)->get() ?? [];

        return view('private.tests.test', compact('test', 'testIndex', 'pendingAnswers'));
    }

    public function handleTestSubmit(Request $request, $testIndex)
    {

        $test = Test::query()
            ->where('order', '=', $testIndex)
            ->with('questions.options')
            ->first();

        $validationRules = $this->generateValidationRules($test);
        $validatedData = $request->validate($validationRules);

        $this->testService->process($validatedData, $test);

        $totalTests = Test::max('order');
        if ($testIndex == $totalTests) {
            $testAnswers = $this->getTestAnswersFromSession();
            $storedAnswers = $this->storeResultsOnDatabase($testAnswers);

            if (! $storedAnswers) {
                return back();
            }

            $request->session()->forget(array_keys($testAnswers));

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

    private function getTestAnswersFromSession(): array
    {
        $testAnswers = collect(session()->all())
            ->filter(function ($_, $key) {
                return str_ends_with($key, '|result');
            })
            ->toArray();

        return $testAnswers;
    }

    private function storeResultsOnDatabase($testAnswers): array
    {
        $testAnswersByCollection = [];

        foreach ($testAnswers as $testName => $testResult) {
            $nameExploded = explode('|', $testName);
            $testCollection = $nameExploded[0];
            $test = $nameExploded[1];
            $testAnswersByCollection[$testCollection][$test] = $testResult;
        }

        DB::transaction(function () use ($testAnswersByCollection) {
            PendingTestAnswer::query()->where('user_id', Auth::user()->id)->delete();

            foreach ($testAnswersByCollection as $collectionName => $collection) {
                $relatedCollection = Collection::where('key_name', $collectionName)->first();
                $newTestCollection = UserCollection::create([
                    'user_id' => Auth::user()->id,
                    'collection_id' => $relatedCollection->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $tests = Test::with('questions.options')->get();

                foreach ($collection as $testName => $testAnswers) {
                    $testType = $tests->where('key_name', $testName)->firstOrFail();

                    $userTest = UserTest::create([
                        'user_collection_id' => $newTestCollection->id,
                        'test_id' => $testType->id,
                        'score' => '',
                        'severity_title' => '',
                        'severity_color' => '',
                    ]);

                    foreach ($testAnswers as $questionId => $answer) {
                        $question = $testType->questions->where('id', $questionId)->first();
                        $option = $question->options->where('value', $answer)->first();

                        DB::table('user_answers')->insert([
                            'question_option_id' => $option->id,
                            'question_id' => $questionId,
                            'user_test_id' => $userTest->id,
                        ]);
                    }
                }
            }
        });

        return $testAnswersByCollection;
    }
}
