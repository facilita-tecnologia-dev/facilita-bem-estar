<?php

namespace App\Handlers;

use App\Models\TestType;
use Illuminate\Contracts\Container\Container;

class TestHandlerFactory
{
    public function __construct(private Container $app) {}

    public function getHandler(TestType $testInfo): TestHandlerInterface
    {
        if (! $testInfo) {
            return $this->app->make(DefaultTestHandler::class);
        }

        return match ($testInfo->handler_type) {
            'work-context' => $this->app->make(WorkContextHandler::class),
            'management-style' => $this->app->make(ManagementStyleHandler::class),
            'work-experiences' => $this->app->make(WorkExperienceHandler::class),
            'work-problems' => $this->app->make(WorkProblemsHandler::class),
            'anxiety' => $this->app->make(AnxietyTestHandler::class),
            'depression' => $this->app->make(DepressionTestHandler::class),
            'pressure-at-work' => $this->app->make(PressureAtWorkTestHandler::class),
            'pressure-for-results' => $this->app->make(PressureForResultsTestHandler::class),
            'insecurity' => $this->app->make(InsecurityTestHandler::class),
            'conflicts' => $this->app->make(ConflictsTestHandler::class),
            'social-relations' => $this->app->make(SocialRelationsTestHandler::class),
            'emotional-demands' => $this->app->make(EmotionalDemandsTestHandler::class),
            'autonomy' => $this->app->make(AutonomyTestHandler::class),
            'burnout' => $this->app->make(BurnoutTestHandler::class),
            'stress' => $this->app->make(StressTestHandler::class),
            default => $this->app->make(DefaultTestHandler::class),
        };
    }
}
