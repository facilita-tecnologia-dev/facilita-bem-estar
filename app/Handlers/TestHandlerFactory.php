<?php

namespace App\Handlers;

use App\Models\TestType;
use App\Repositories\TestRepository;

class TestHandlerFactory
{
    public function getHandler(TestType $testInfo): TestHandlerInterface
    {   
        if (!$testInfo) {
            return new DefaultTestHandler();
        }

        switch ($testInfo->handler_type) {
            case 'work-context':
                return new AnxietyTestHandler();
            case 'management-style':
                return new DepressionTestHandler();
            case 'work-experiences':
                return new PressureAtWorkTestHandler();
            case 'work-problems':
                return new PressureForResultsTestHandler();

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