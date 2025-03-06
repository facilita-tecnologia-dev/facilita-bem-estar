<?php

namespace App\Handlers;

use App\Models\TestType;
use App\Repositories\TestRepository;

class TestHandlerFactory
{
    public function getHandler(string $test, TestType $testInfo): TestHandlerInterface
    {
        $testInfo = $testInfo;
        
        if (!$testInfo) {
            return new DefaultTestHandler();
        }
        
        switch ($testInfo['handler_type']) {
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