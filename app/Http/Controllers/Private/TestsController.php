<?php

namespace App\Http\Controllers\Private;

use App\Helpers\AuthGuardHelper;
use App\Models\CustomCollection;
use App\Models\UserAnswer;
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

    public function __invoke(CustomCollection $collection, $testIndex = 1)
    {
        $tests = $collection->tests;
        $test = $tests->firstWhere('order', $testIndex);
        
        if($test == null){
            while($test == null){   
                $testIndex++;
                $test = $tests->firstWhere('order', $testIndex);
            }
        }

        $collectionType = $collection->collectionType;

        // $pendingAnswers = PendingTestAnswer::query()->where('user_id', '=', AuthGuardHelper::user()->id)->where('test_id', '=', $test->id)->get();

        return view('private.tests.test', compact('test', 'testIndex', /*'pendingAnswers',*/ 'collection', 'collectionType'));
    }

    public function handleTestSubmit(Request $request, CustomCollection $collection, $testIndex)
    {
        $tests = $collection->tests;
        $test = $tests->firstWhere('order', $testIndex);
        
        $validationRules = $this->generateValidationRules($test);
        $validatedData = $request->validate($validationRules);
        
        $this->testService->process($collection, $test, $validatedData);

        if ($testIndex == $tests->max('order')) {
            $testAnswers = $this->getTestAnswersFromSession($collection);
            $storedAnswers = $this->storeResultsOnDatabase($collection, $testAnswers);

            if (! $storedAnswers) {
                return back();
            }

            $request->session()->forget(array_keys($testAnswers));

            if ($collection['collection_id'] == 2) {
                return to_route('feedbacks.create');
            }

            return to_route('responder-teste.thanks');
        }

        return to_route('responder-teste', [$collection, $testIndex + 1]);
    }

    private function generateValidationRules($test): array
    {
        $validationRules = $test->questions->mapWithKeys(function($question, $id){
            return [$question->id => 'required'];
        });

        return $validationRules->toArray();
    }

    private function getTestAnswersFromSession(CustomCollection $collection): array
    {
        $testAnswers = collect(session()->all())
            ->filter(function ($_, $key) use ($collection) {
                return str_ends_with($key, '|result') && str_contains($key, $collection->key_name);
            })
            ->toArray();

        return $testAnswers;
    }

    private function storeResultsOnDatabase(CustomCollection $collection, array $testAnswers): array
    {
        $compiledTestAnswers = Collect($testAnswers)->mapWithKeys(function($answers, $testSessionKey){
            $sessionKeyExploded = explode('|', $testSessionKey);
            $testKeyName = $sessionKeyExploded[1];

            return [$testKeyName => $answers];
        });

        $tests = $collection->tests;
        
        DB::transaction(function() use($collection, $tests, $compiledTestAnswers){
            $newUserCollection = UserCollection::create([
                'user_id' => AuthGuardHelper::user()->id,
                'collection_id' => $collection->id,
                'company_id' => session('company')->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $tests->each(function($test) use($newUserCollection, $compiledTestAnswers) {
                $userTest = UserTest::create([
                    'user_collection_id' => $newUserCollection->id,
                    'test_id' => $test->id,
                ]);

                $testAnswers = $compiledTestAnswers[$test->key_name];

                $test->questions->each(function($question) use($userTest, $testAnswers) {
                    $questionAnswer = $testAnswers[$question->id];

                    $option = $question->options->firstWhere('value', $questionAnswer);
                    
                    UserAnswer::create([
                        'question_option_id' => $option->id,
                        'question_id' => $question->id,
                        'user_test_id' => $userTest->id,
                        'user_id' => AuthGuardHelper::user()->id,
                        'value' => $questionAnswer,
                    ]);
                });  
            });
        });

        return $compiledTestAnswers->toArray();
    }

    private function getTestsFromDatabase(CustomCollection $collection, EloquentCollection $defaultTestsOnDatabase,  EloquentCollection $customTestsOnDatabase){
        $newCustomTestsOnDatabase = $customTestsOnDatabase->whereNull('test_id');

        $compiled = $defaultTestsOnDatabase->map(function($test) use($collection, $customTestsOnDatabase) {
            $relatedCustomTest = $customTestsOnDatabase
                ->where('company_id', session('company')->id)
                ->where('collection_id', $collection->id)
                ->where('test_id', $test->id)
                ->first();

            if($relatedCustomTest){
                $mergedQuestions = $test->questions->map(function($question) use($relatedCustomTest) {
                    $relatedCustomQuestion = $relatedCustomTest->questions->firstWhere('question_id', $question->id);
                    return $relatedCustomQuestion ?? $question;
                });
                
                $customTestQuestions = $relatedCustomTest->questions->whereNull('question_id');
                
                $mergedQuestions = $mergedQuestions->merge($customTestQuestions)->sortBy('is_deleted');
                
                
                $test->setRelation('questions', $mergedQuestions);
            }

            return $test;
        });

        $compiled = $compiled->merge($newCustomTestsOnDatabase);

        $compiled = $compiled->keyBy('display_name');

        return $compiled;
    }
}
