<?php 

namespace App\Services;

use App\Handlers\TestHandlerFactory;
use App\Models\TestType;
use App\Repositories\TestRepository;

class TestService
{
    protected $handlerFactory;

    public function __construct(TestHandlerFactory $handlerFactory)
    {
        $this->handlerFactory = $handlerFactory;
    }

    public function processTest(string $test, array $validatedData, TestType $testInfo){
        $answers = collect($validatedData)
        ->filter(function ($value, $key) {
            return str_starts_with($key, 'question_');
        })
        ->mapWithKeys(function ($value, $key) {
            $questionNumber = substr($key, strlen('question_'));
            return [$questionNumber => (int) $value];
        })
        ->toArray();

        
        $handler = $this->handlerFactory->getHandler($test, $testInfo);
        
        $processedTest = $handler->process($answers);
        $result = array_merge(['testName' => $testInfo['display_name']], $processedTest);

        // Armazena os resultados na sessão
        session([$test . '_result' => $result]);
        
        return $result;
    }
}