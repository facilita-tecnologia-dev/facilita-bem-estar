<?php

namespace App\Services;

use App\Handlers\TestHandlerFactory;
use App\Models\Company;
use App\Models\PendingTestAnswer;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;

class TestService
{
    protected $handlerFactory;

    public $companyUsersCollections;

    public function __construct(TestHandlerFactory $handlerFactory)
    {
        $this->handlerFactory = $handlerFactory;
    }

    /**
     * Retorna um objeto Company com a última coleção de testes de cada usuário da empresa.
     */
    // public function getTests($justEssentials = null, $testName = null, $risks = null, $metrics = null, $tests = true, $queryStringName = null, $queryStringDepartment = null, $queryStringOccupation = null): Company
    // {
    //     $companyUsersCollections = Company::where('id', session('company')->id)
    //         ->when($metrics, function ($q) {
    //             $q->with('metrics.metricType');
    //         })
    //         ->with('users', function ($user) use ($justEssentials, $testName, $tests, $queryStringName, $queryStringDepartment, $queryStringOccupation) {
    //             $user
    //                 ->has('collections')
    //                 ->when($queryStringName, function ($query) use ($queryStringName) {
    //                     $query->where('name', 'like', "%$queryStringName%");
    //                 })
    //                 ->when($queryStringDepartment, function ($query) use ($queryStringDepartment) {
    //                     $query->where('department', '=', "$queryStringDepartment");
    //                 })
    //                 ->when($queryStringOccupation, function ($query) use ($queryStringOccupation) {
    //                     $query->where('occupation', '=', "$queryStringOccupation");
    //                 })
    //                 ->with('latestCollections', function ($latestCollection) use ($justEssentials, $testName, $tests) {
    //                     $latestCollection
    //                         ->with('collectionType')
    //                         ->when($tests, function ($q) use ($justEssentials, $testName) {
    //                             $q->with('tests', function ($userTest) use ($justEssentials, $testName) {
    //                                 $userTest
    //                                     ->when($testName, function ($q) use ($testName) {
    //                                         $q->whereHas('testType', function ($query) use ($testName) {
    //                                             $query->where('display_name', $testName);
    //                                         });
    //                                     })
    //                                     ->when($justEssentials, function ($q) {
    //                                         $q->with(['testType.risks.relatedQuestions']);
    //                                     })
    //                                     ->when(! $justEssentials, function ($q) {
    //                                         $q->with(['answers', 'questions.options', 'testType.risks.relatedQuestions']);
    //                                     });
    //                             });
    //                         });
    //                 });
    //         })
    //         ->first();

    //     return $companyUsersCollections;
    // }

    public function process(array $answers, Test $test)
    {
        $answersValues = array_map(function ($value) {
            return (int) $value;
        }, $answers);

        $parentCollection = $test->parentCollection->key_name;
        session(["$parentCollection|$test->key_name|result" => $answersValues]);

        $pendingAnswers = [];
        $questions = $test->questions()->get()->keyBy('id');

        foreach ($answersValues as $questionId => $answer) {
            $question = $questions[$questionId];
            $option = $question->options->firstWhere('value', $answer);

            if ($option) {
                $pendingAnswers[] = [
                    'value' => $answer,
                    'question_option_id' => $option->id,
                    'question_id' => $questionId,
                    'test_id' => $test->id,
                    'user_id' => Auth::user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (! empty($pendingAnswers)) {
            PendingTestAnswer::where('test_id', '=', $pendingAnswers[0]['test_id'])->delete();
            PendingTestAnswer::insert($pendingAnswers);
        }

        return true;
    }

    public function evaluateTest($userTest, $metrics)
    {
        $handler = $this->handlerFactory->getHandler($userTest->testType);
        $evaluatedTest = $handler->process($userTest, $metrics);

        return $evaluatedTest;
    }
}
