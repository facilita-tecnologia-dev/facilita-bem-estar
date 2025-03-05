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

        $testInfo = $this->getTestInfo($test);
        
        $processedTest = $handler->process($answers);
        $result = array_merge(['testName' => $testInfo['displayName']], $processedTest);

        // dd('processed test', $processedTest, 'result', $result);

        // Armazena os resultados na sessão
        session([$test . '_result' => $result]);
        
        return $result;
    }
}