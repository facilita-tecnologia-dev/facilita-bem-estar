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
        // Transforma cada resposta em pontos (numero inteiro)
        $answers = array_map(function($value){
            return (int) $value;
        }, $validatedData);

        // Busca o handler do teste atual
        $handler = $this->handlerFactory->getHandler($testInfo);
        
        // Processa o teste
        $processedTest = $handler->process($answers);

        $result = array_merge(['test_name' => $testInfo['display_name']], $processedTest);
        
        // Armazena os resultados na sessão
        session([$testInfo['key_name'] . '_result' => $result]);
        
        return $result;
    }
}