<?php 

namespace App\Services;

use App\Handlers\TestHandlerFactory;
use App\Repositories\TestRepository;

class TestService
{
    protected $testRepository;
    protected $handlerFactory;

    public function __construct(TestRepository $testRepository, TestHandlerFactory $handlerFactory)
    {
        $this->testRepository = $testRepository;
        $this->handlerFactory = $handlerFactory;
    }

    public function testExists(string $test){
        return $this->testRepository->exists($test);
    }

    public function getTestInfo(string $test){
        return $this->testRepository->getTestInfo($test);
    }

    public function processTest(string $test, array $validatedData){
        $answers = collect($validatedData)
        ->filter(function ($value, $key) {
            return str_starts_with($key, 'question_');
        })
        ->mapWithKeys(function ($value, $key) {
            $questionNumber = substr($key, strlen('question_'));
            return [$questionNumber => (int) $value];
        })
        ->toArray();

        $handler = $this->handlerFactory->getHandler($test);

        $result = $handler->process($answers);

        $totalPoints = collect($answers)->sum();

        // Armazena os resultados na sessão
        session([$test . '_answers' => $answers]);
        session([$test . '_total_points' => $totalPoints]);
        
        // Adiciona a categoria ao resultado para referência futura
        $result['total_points'] = $totalPoints;
        
        return $result;
    }

    // public function getAllResults(): array
    // {
    //     $results = [];
    //     $tests = $this->testRepository->getAllTests();
        
    //     foreach ($tests as $testName => $testInfo) {
    //         if (session()->has($testName . '_total_points')) {
    //             $results[$testName] = [
    //                 'name' => $testName,
    //                 'display_name' => $testInfo['displayName'],
    //                 'total_points' => session($testName . '_total_points'),
    //                 'answers' => session($testName . '_answers'),
    //             ];
    //         }
    //     }
        
    //     return $results;
    // }
}