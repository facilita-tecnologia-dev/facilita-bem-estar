<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CustomQuestion;
use App\Models\CustomQuestionOption;
use App\Models\CustomTest;
use App\Models\Test;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Pest\ArchPresets\Custom;

class CustomCollectionsController
{
    public function showCompanyCollections(){
        $collections = Collection::withCount('tests')->get();

        return view('private.tests.collections', compact('collections'));
    }

    public function editCollection(Collection $collection)
    {   
        $defaultTestsOnDatabase = $collection->tests()->with('questions')->get();
        $customTestsOnDatabase = $collection->customTests()->with('questions')->get();

        $testsToUpdate = $this->getTestsFromDatabase($collection, $defaultTestsOnDatabase, $customTestsOnDatabase);

        // $newCustomTests = $customTests->filter(function($customTest) {
        //     return !$customTest->test_id;
        // });

        
        
        // $testsToUpdate = $defaultTests;

        // dd($testsOnDatabase->first()->questions);

        // foreach($defaultTests as $defaultTest){
        //     $customTestData = $customTests->where('company_id', session('company')->id)
        //     ->where('collection_id', $collection->id)
        //     ->where('test_id', $defaultTest->id)
        //     ->first();

        //     if($customTestData){
        //         $customTestQuestions = $customTestData->questions;

        //         $replacedQuestions = $defaultTest->questions->map(function ($question) use ($customTestQuestions) {
        //             $customQuestion = $customTestQuestions
        //                 ->where('company_id', session('company')->id)
        //                 ->where('question_id', $question->id)
        //                 ->first();
            
        //             return $customQuestion ?: $question;
        //         });
        
        //         $customQuestionsToAdd = $customTestQuestions->filter(function($customQuestion) use($defaultTest) {
        //             return !$defaultTest->questions->contains('id', $customQuestion->question_id);
        //         });

        //         $allQuestions = $replacedQuestions->concat($customQuestionsToAdd)->values();

        //         $testToUpdate = $testsToUpdate->firstWhere('id', $defaultTest->id);
        //         $testToUpdate->setRelation('questions', $allQuestions);
        //     }
        // }

        // $testsToUpdate = $testsToUpdate->merge($newCustomTests);
        
        return view('private.tests.collections.update', compact('collection', 'testsToUpdate'));
    }

    public function updateCollection(Request $request, Collection $collection)
    {
        DB::transaction(function() use($request, $collection){
            $defaultTestsOnDatabase = $collection->tests()->with('questions')->get();
            $customTestsOnDatabase = $collection->customTests()->with('questions')->get();

            $testsOnRequest = $this->getTestsFromRequest($request);
            $testsOnDatabase = $this->getTestsFromDatabase($collection, $defaultTestsOnDatabase, $customTestsOnDatabase);
            
            $newTestsToStore = $testsOnRequest->diffKeys($testsOnDatabase);

            $this->storeNewCustomTestsOnDatabase($collection, $newTestsToStore, $testsOnDatabase);

            // Tests to recover
            $testsOnRequest->mapWithKeys(function($test, $testName) use($customTestsOnDatabase) {
                $relatedCustomTestOnDatabase = $customTestsOnDatabase->where('display_name', $testName)->where('is_deleted', 1)->first();

                return $relatedCustomTestOnDatabase ? [$testName => $relatedCustomTestOnDatabase] : [];
            })->each(function($test){
                $test->is_deleted = 0;
                $test->save();
            });


            // Tests to delete
            $testsOnDatabase->diffKeys($testsOnRequest)
            ->each(function($test, $testName) use($collection, $customTestsOnDatabase) {
                $relatedCustomTest = $customTestsOnDatabase->firstWhere('display_name', $testName);
                if($relatedCustomTest){
                    if(!$relatedCustomTest->is_deleted){
                        $relatedCustomTest->is_deleted = true;
                        $relatedCustomTest->save();
                    }
                } else{
                    CustomTest::create([
                        'company_id' => session('company')->id,
                        'collection_id' => $collection->id,
                        'test_id' => $test->id,
                        'key_name' => $test->key_name,
                        'display_name' => $test->display_name,
                        'statement' => $test->statement,
                        'reference' => $test->reference,
                        'number_of_questions' => $test->number_of_questions,
                        'order' => $test->order,
                        'is_deleted' => 1,
                    ]);
                }
            });

            // Questions to recover
            $testsOnRequest->each(function($questions, $testName) use($customTestsOnDatabase){
                $relatedCustomTest = $customTestsOnDatabase->firstWhere('display_name', $testName);
                
                if($relatedCustomTest){
                    $relatedCustomTest->questions
                    ->each(function($question) use($questions) {
                        $questionStatement = $question->statement == "" ? $question->relatedQuestion->statement : $question->statement;
                        $relatedQuestionOnRequest = Collect($questions)->first(fn($q) => $q == $questionStatement);
                        
                        if($relatedQuestionOnRequest){
                            $question->is_deleted = false;
                            $question->save();
                        }
                    }); 
                }
            });

            // Questions to delete
            $testsOnDatabase->each(function($test, $testName) use($testsOnRequest) {
                $relatedTestOnRequest = $testsOnRequest->first(fn($q, $key) => $key == $testName);

                if($relatedTestOnRequest){
                    $testQuestions = $test->questions
                    ->map(function($question){
                        return $question->statement == "" ? $question->relatedQuestion->statement : $question->statement;
                    });

                    $questionsToDelete = $testQuestions->diff($relatedTestOnRequest)
                    ->each(function($question) use($test) {
                        $relatedCustomQuestionOnDatabase = $test->questions
                        ->map(function($question){
                            $question->statement = $question->statement == "" ? $question->relatedQuestion->statement : $question->statement;
                            return $question;
                        })
                        ->first(fn($q) => $q->statement == $question);

                        if($relatedCustomQuestionOnDatabase){
                            if(!$relatedCustomQuestionOnDatabase->is_deleted){
                                $relatedCustomQuestionOnDatabase->is_deleted = 1;
                                $relatedCustomQuestionOnDatabase->save();
                            }
                        } else{
                            CustomQuestion::create([
                                'company_id' => session('company')->id,
                                'custom_test_id' => $test->id,
                                'question_id' => $relatedCustomQuestionOnDatabase->id,
                                'statement' => $relatedCustomQuestionOnDatabase->statement,
                                'is_deleted' => true,
                            ]);
                        }
                    });
                } else{
                    $test->questions->each(function($question){
                        $question->is_deleted = true;
                        $question->save();
                    });
                }
            });

            // Questions to add
            $testsOnRequest->each(function($questions, $testName) use($collection, $testsOnDatabase) {
                $relatedTestOnDatabase = $testsOnDatabase->firstWhere('display_name', $testName);
                if($relatedTestOnDatabase){
                    $relatedTestOnDatabaseQuestions = $relatedTestOnDatabase->questions
                    ->map(function($question){
                        return $question->statement == "" ? $question->relatedQuestion->statement : $question->statement;
                    });
                    
                    $collectionLastTest = $collection->tests()->with('questions')->orderBy('order', 'desc')->first();

                    $questionsToCreate = Collect($questions)->diff($relatedTestOnDatabaseQuestions)
                    ->each(function($question) use($relatedTestOnDatabase, $collectionLastTest) {
                        $customQuestion = CustomQuestion::create([
                            'company_id' => session('company')->id,
                            'custom_test_id' => $relatedTestOnDatabase->customTest->id,
                            'question_id' => null,
                            'statement' => $question,
                            'is_deleted' => 0,
                        ]);

                        foreach($collectionLastTest->questions[0]->options as $option){
                            CustomQuestionOption::create([
                                'company_id' => session('company')->id,
                                'custom_question_id' => $customQuestion->id,
                                'question_option_id' => null,
                                'content' => $option->content,
                                'value' => $option->value,
                                'is_deleted' => 0,
                            ]);
                        }
                    });
                }
            });
        });

        return back()->with('message', 'Testes atualizados com sucesso!');
    }

    private function getTestsFromRequest(Request $request){
        $submittedTests = array_filter($request->all(), fn($key) => str_ends_with($key, '_questions'), ARRAY_FILTER_USE_KEY);

        $formattedTestList = [];
        
        foreach($submittedTests as $testName => $questions){
            $formattedTestName = str_replace('_', ' ', str_replace('_questions', '', $testName));
            $formattedTestList[$formattedTestName] = $questions;
        }

        return Collect($formattedTestList);
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

    private function storeNewCustomTestsOnDatabase(Collection $collection, SupportCollection $newTestsToStore, EloquentCollection $testsOnDatabase){
        $collectionLastTest = $testsOnDatabase->sortByDesc('order')->first();

        foreach($newTestsToStore as $testName => $questions){
            $customTest = CustomTest::create([
                'company_id' => session('company')->id,
                'collection_id' => $collection->id,
                'test_id' => null, //pois é novo
                'key_name' => Str::slug($testName),
                'display_name' => $testName,
                'statement' => $collection->tests[0]->statement,
                'reference' => '-',
                'number_of_questions' => count($questions),
                'order' => $collectionLastTest->order + 1,
                'is_deleted' => 0,
            ]);

            foreach($questions as $question) {
                $customQuestion = CustomQuestion::create([
                    'company_id' => session('company')->id,
                    'custom_test_id'=> $customTest->id,
                    'question_id' => null, //pois é novo
                    'statement' => $question,
                    'is_deleted' => 0,
                ]);

                foreach($collectionLastTest->questions[0]->options as $option){
                    CustomQuestionOption::create([
                        'company_id' => session('company')->id,
                        'custom_question_id' => $customQuestion->id,
                        'question_option_id' => null,
                        'content' => $option->content,
                        'value' => $option->value,
                        'is_deleted' => 0,
                    ]);
                }
            }
        }
    }
}
