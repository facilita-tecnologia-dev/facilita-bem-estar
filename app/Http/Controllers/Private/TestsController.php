<?php

namespace App\Http\Controllers\Private;

use App\Helpers\AuthGuardHelper;
use App\Models\Collection;
use App\Models\CustomQuestion;
use App\Models\CustomTest;
use App\Models\PendingTestAnswer;
use App\Models\Test;
use App\Models\UserCollection;
use App\Models\UserTest;
use App\Services\TestService;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestsController
{
    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function __invoke(Collection $collection, $testIndex = 1)
    {
        $defaultTestsOnDatabase = $collection->tests()->with('questions')->get();
        $customTestsOnDatabase = $collection->customTests()->with('questions')->get();

        $tests = $this->getTestsFromDatabase($collection, $defaultTestsOnDatabase, $customTestsOnDatabase)->filter(function($test){
            if(!$test instanceof CustomTest){
                return !$test->customTest->is_deleted;
            }
            return true;
        });

        $test = $tests->firstWhere('order', $testIndex);
        
        while($test == null){   
            $testIndex++;
            $test = $tests->firstWhere('order', $testIndex);
        }


        $mergedQuestions = $test->questions->where('is_deleted', false)->map(function($question){
            $relatedDefaultQuestion = $question->relatedQuestion;

            return $relatedDefaultQuestion ? $relatedDefaultQuestion : $question;
        });

        $test->setRelation('questions', $mergedQuestions);

        dump(session()->all());
        // $pendingAnswers = PendingTestAnswer::query()->where('user_id', '=', AuthGuardHelper::user()->id)->where('test_id', '=', $test->id)->get();

        return view('private.tests.test', compact('test', 'testIndex', /*'pendingAnswers',*/ 'collection'));
    }

    public function handleTestSubmit(Request $request, Collection $collection, $testIndex)
    {
        $defaultTestsOnDatabase = $collection->tests()->with('questions')->get();
        $customTestsOnDatabase = $collection->customTests()->with('questions')->get();

        $tests = $this->getTestsFromDatabase($collection, $defaultTestsOnDatabase, $customTestsOnDatabase);

        $test = $tests->firstWhere('order', $testIndex);

        $mergedQuestions = $test->questions->where('is_deleted', false)->map(function($question){
            $relatedDefaultQuestion = $question->relatedQuestion;

            return $relatedDefaultQuestion ? $relatedDefaultQuestion : $question;
        });

        $test->setRelation('questions', $mergedQuestions);

        $validationRules = $this->generateValidationRules($test);
        $validatedData = $request->validate($validationRules);

        $this->testService->process($validatedData, $test);

        $totalTests = Test::where('collection_id', $collection->id)->max('order');
        if ($testIndex == $totalTests) {
            $testAnswers = $this->getTestAnswersFromSession($collection);
            $storedAnswers = $this->storeResultsOnDatabase($testAnswers);

            if (! $storedAnswers) {
                return back();
            }

            $request->session()->forget(array_keys($testAnswers));

            if ($collection->key_name == 'organizational-climate') {
                return to_route('feedbacks.create');
            }

            return to_route('responder-teste.thanks');
        }

        return to_route('responder-teste', [$collection, $testIndex + 1]);
    }

    private function generateValidationRules($test): array
    {
        $validationRules = [];

        // $testQuestions = $test->questions->groupBy('id');

        foreach ($test->questions as $question) {
            if($question instanceof CustomQuestion){
                if($question->question_id){
                    $validationRules[$question->question_id] = 'required';
                }else{
                    $validationRules[$question->id] = 'required';
                }
            } else{
                $validationRules[$question->id] = 'required';
            }
        }

        return $validationRules;
    }

    private function getTestAnswersFromSession(Collection $collection): array
    {
        $testAnswers = collect(session()->all())
            ->filter(function ($_, $key) use ($collection) {
                return str_ends_with($key, '|result') && str_contains($key, $collection->key_name);
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
            PendingTestAnswer::query()->where('user_id', AuthGuardHelper::user()->id)->delete();

            foreach ($testAnswersByCollection as $collectionName => $collection) {
                $relatedCollection = Collection::where('key_name', $collectionName)->first();
                $newTestCollection = UserCollection::create([
                    'user_id' => AuthGuardHelper::user()->id,
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
                        $option = $question['options']->where('value', $answer)->first();

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

    private function getTestsFromDatabase(Collection $collection, EloquentCollection $defaultTestsOnDatabase,  EloquentCollection $customTestsOnDatabase){
        $newCustomTestsOnDatabase = $customTestsOnDatabase->whereNull('test_id');

        $compiled = $defaultTestsOnDatabase->map(function($test) use($collection, $customTestsOnDatabase) {
            $relatedCustomTest = $customTestsOnDatabase
                ->where('company_id', session('company')->id)
                ->where('collection_id', $collection->id)
                ->where('test_id', $test->id)
                ->first();
    
            $mergedQuestions = $test->questions->map(function($question) use($relatedCustomTest) {
                $relatedCustomQuestion = $relatedCustomTest->questions->firstWhere('question_id', $question->id);
                return $relatedCustomQuestion ?? $question;
            });

            $customTestQuestions = $relatedCustomTest->questions->whereNull('question_id');
 
            $mergedQuestions = $mergedQuestions->merge($customTestQuestions)->sortBy('is_deleted');


            $test->setRelation('questions', $mergedQuestions);

            return $test;
        });

        $compiled = $compiled->merge($newCustomTestsOnDatabase);

        $compiled = $compiled->keyBy('display_name');

        return $compiled;
    }

}
