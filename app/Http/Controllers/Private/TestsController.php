<?php

namespace App\Http\Controllers\Private;

use App\Helpers\AuthGuardHelper;
use App\Models\Collection;
use App\Models\CustomQuestion;
use App\Models\CustomTest;
use App\Models\PendingTestAnswer;
use App\Models\Test;
use App\Models\UserAnswer;
use App\Models\UserCollection;
use App\Models\UserCustomAnswer;
use App\Models\UserCustomTest;
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
            if($test instanceof CustomTest){
                return !$test->is_deleted;
            }
            
            if($test->customTest){
                return !$test->customTest->is_deleted;
            }

            return true;
        });

        $test = $tests->firstWhere('order', $testIndex);
 
        if($test == null){
            while($test == null){   
                $testIndex++;
                $test = $tests->firstWhere('order', $testIndex);
            }
        }

        $mergedQuestions = $test->questions->where('is_deleted', false)->map(function($question){
            $relatedDefaultQuestion = $question->relatedQuestion;

            return $relatedDefaultQuestion ? $relatedDefaultQuestion : $question;
        });

        $test->setRelation('questions', $mergedQuestions->shuffle());


        // $pendingAnswers = PendingTestAnswer::query()->where('user_id', '=', AuthGuardHelper::user()->id)->where('test_id', '=', $test->id)->get();

        return view('private.tests.test', compact('test', 'testIndex', /*'pendingAnswers',*/ 'collection'));
    }

    public function handleTestSubmit(Request $request, Collection $collection, $testIndex)
    {
        $defaultTestsOnDatabase = $collection->tests()->with('questions')->get();
        $customTestsOnDatabase = $collection->customTests()->with('questions')->get();

        $tests = $this->getTestsFromDatabase($collection, $defaultTestsOnDatabase, $customTestsOnDatabase)->filter(function($test){
            if($test instanceof CustomTest){
                return !$test->is_deleted;
            }
            
            if($test->customTest){
                return !$test->customTest->is_deleted;
            }

            return true;
        });
        
        $test = $tests->firstWhere('order', $testIndex);

        $mergedQuestions = $test->questions->where('is_deleted', false)->map(function($question){
            $relatedDefaultQuestion = $question->relatedQuestion;

            return $relatedDefaultQuestion ? $relatedDefaultQuestion : $question;
        });

        $test->setRelation('questions', $mergedQuestions);
        
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

            if ($collection->key_name == 'organizational-climate') {
                return to_route('feedbacks.create');
            }

            return to_route('responder-teste.thanks');
        }

        return to_route('responder-teste', [$collection, $testIndex + 1]);
    }

    private function generateValidationRules($test): array
    {
        $validationRules = $test->questions->mapWithKeys(function($question, $id){
            if($question instanceof CustomQuestion){
                if($question->question_id){
                    return ['custom_' . $question->question_id => 'required'];
                }else{
                    return ['custom_' . $question->id => 'required'];
                }
            } else{
                return ['default_' . $question->id => 'required'];
            }
        });

        return $validationRules->toArray();
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

    private function storeResultsOnDatabase(Collection $collection, array $testAnswers): array
    {
        $compiledTestAnswers = Collect($testAnswers)->mapWithKeys(function($answers, $testSessionKey){
            $sessionKeyExploded = explode('|', $testSessionKey);
            $testKeyName = $sessionKeyExploded[1];

            return [$testKeyName => $answers];
        });

        $defaultTestsOnDatabase = $collection->tests()->with('questions')->get();
        $customTestsOnDatabase = $collection->customTests()->with('questions')->get();

        $tests = $this->getTestsFromDatabase($collection, $defaultTestsOnDatabase, $customTestsOnDatabase)->filter(function($test){
            if($test instanceof CustomTest){
                return !$test->is_deleted;
            }
            
            if($test->customTest){
                return !$test->customTest->is_deleted;
            }

            return true;
        });

        $defaultTestsOnRequest = collect();
        $customTestsOnRequest = collect();

        foreach($compiledTestAnswers as $testKeyName => $answers){
            $relatedTestOnDatabase = $tests->firstWhere('key_name', $testKeyName);

            $defaultQuestionAnswers = collect();
            $customQuestionAnswers = collect();

            foreach($answers as $key => $value){
                if(str_starts_with($key, 'default')){
                    $defaultQuestionAnswers->put($key, $value);
                } else{
                    $customQuestionAnswers->put($key, $value);
                }
            }

            $answers = [
                'default' => $defaultQuestionAnswers,
                'custom' => $customQuestionAnswers
            ];

            if($relatedTestOnDatabase instanceof CustomTest){
                $customTestsOnRequest->put($testKeyName, $answers);
            }else{
                $defaultTestsOnRequest->put($testKeyName, $answers);
            }
        }
        
        DB::transaction(function() use($collection, $defaultTestsOnRequest, $customTestsOnRequest, $tests){
            $newUserCollection = UserCollection::create([
                'user_id' => AuthGuardHelper::user()->id,
                'collection_id' => $collection->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $defaultTestsOnRequest->each(function($defaultTest, $testKeyName) use($newUserCollection, $tests) {
                // criar o user_test
                $relatedTestOnDatabase = $tests->firstWhere('key_name', $testKeyName);

                $mergedQuestions = $relatedTestOnDatabase->questions->where('is_deleted', false)->map(function($question){
                    $relatedDefaultQuestion = $question->relatedQuestion;
                    return $relatedDefaultQuestion ? $relatedDefaultQuestion : $question;
                });



                $userTest = UserTest::create([
                    'user_collection_id' => $newUserCollection->id,
                    'test_id' => $relatedTestOnDatabase->id,
                ]);

                $hasDefaultQuestions = isset($defaultTest['default']) && count($defaultTest['default']) > 0;
       
                if($hasDefaultQuestions){    
                    // armazenar respostas padrão
                    Collect($defaultTest['default'])->each(function($defaultQuestionAnswer, $key) use($userTest, $mergedQuestions){
                        $questionId = str_replace('default_', '', $key);

                        $relatedQuestionOnDatabase = $mergedQuestions->firstWhere('id', $questionId);            
                        $relatedOptionOnDatabase = $relatedQuestionOnDatabase->options->firstWhere('value', $defaultQuestionAnswer);
                        
                        UserAnswer::create([
                            'question_option_id' => $relatedOptionOnDatabase->id,
                            'question_id' => $relatedQuestionOnDatabase->id,
                            'user_test_id' => $userTest->id,
                        ]);
                    });
                }

                $hasCustomQuestions = isset($defaultTest['custom']) && count($defaultTest['custom']) > 0;

                // se necessário, criar user_custom_test
                if($hasCustomQuestions){
                    $relatedCustomTestOnDatabase = $relatedTestOnDatabase->customTest;

                    $userCustomTest = UserCustomTest::create([
                        'user_collection_id' => $newUserCollection->id,
                        'custom_test_id' => $relatedCustomTestOnDatabase->id,
                    ]);

                    // armazenar respostas custom
                    Collect($defaultTest['custom'])->each(function($customQuestionAnswer, $key) use($userCustomTest, $mergedQuestions){
                        $questionId = str_replace('custom_', '', $key);

                        $relatedCustomQuestionOnDatabase = $mergedQuestions->firstWhere('id', $questionId);            
                        $relatedCustomOptionOnDatabase = $relatedCustomQuestionOnDatabase->options->firstWhere('value', $customQuestionAnswer);

                        UserCustomAnswer::create([
                            'user_custom_test_id' => $userCustomTest->id,
                            'custom_question_id' => $relatedCustomQuestionOnDatabase->id,
                            'custom_question_option_id' => $relatedCustomOptionOnDatabase->id,
                        ]);

                    });
                }
            });

            $customTestsOnRequest->each(function($customTest, $testKeyName) use($newUserCollection, $tests) {
                $relatedCustomTestOnDatabase = $tests->firstWhere('key_name', $testKeyName);
                
                $userCustomTest = UserCustomTest::create([
                    'user_collection_id' => $newUserCollection->id,
                    'custom_test_id' => $relatedCustomTestOnDatabase->id,
                ]);

                Collect($customTest['custom'])->each(function($customQuestionAnswer, $key) use($relatedCustomTestOnDatabase, $userCustomTest){
                    $questionId = str_replace('custom_', '', $key);

                    $relatedCustomQuestionOnDatabase = $relatedCustomTestOnDatabase->questions->firstWhere('id', $questionId);            
                    $relatedCustomOptionOnDatabase = $relatedCustomQuestionOnDatabase->options->firstWhere('value', $customQuestionAnswer);

                    UserCustomAnswer::create([
                        'user_custom_test_id' => $userCustomTest->id,
                        'custom_question_id' => $relatedCustomQuestionOnDatabase->id,
                        'custom_question_option_id' => $relatedCustomOptionOnDatabase->id,
                    ]);
                });

                dump($userCustomTest, $userCustomTest->answers, '-', '-');

            }); 
        });

        return $compiledTestAnswers->toArray();
    }

    private function getTestsFromDatabase(Collection $collection, EloquentCollection $defaultTestsOnDatabase,  EloquentCollection $customTestsOnDatabase){
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
