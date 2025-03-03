<?php

namespace App\Handlers;

use App\Repositories\TestRepository;

class TestHandlerFactory
{
    protected $testRepository;
    
    public function __construct(TestRepository $testRepository)
    {
        $this->testRepository = $testRepository;
    }
    
    public function getHandler(string $test): TestHandlerInterface
    {
        $testInfo = $this->testRepository->getTestInfo($test);
        
        if (!$testInfo) {
            return new DefaultTestHandler();
        }
        
        switch ($testInfo['handlerType']) {
            case 'anxiety':
                return new AnxietyTestHandler();
            case 'depression':
                return new DepressionTestHandler();
            default:
                return new DefaultTestHandler();
        }
    }
}