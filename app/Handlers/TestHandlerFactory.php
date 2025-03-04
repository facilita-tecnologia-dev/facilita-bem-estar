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
            case 'pressure-at-work':
                return new PressureAtWorkTestHandler();
            case 'pressure-for-results':
                return new PressureForResultsTestHandler();
            case 'insecurity':
                return new InsecurityTestHandler();
            case 'conflicts':
                return new ConflictsTestHandler();
            case 'social-relations':
                return new SocialRelationsTestHandler();
            case 'emotional-demands':
                return new EmotionalDemandsTestHandler();
            case 'autonomy':
                return new AutonomyTestHandler();
            case 'burnout':
                return new BurnoutTestHandler();
            case 'stress':
                return new StressTestHandler();
            default:
                return new DefaultTestHandler();
        }
    }
}