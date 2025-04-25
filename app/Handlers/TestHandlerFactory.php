<?php

namespace App\Handlers;

use App\Handlers\OrganizationalClimate\WorkCommunicationAndInformationHandler;
use App\Handlers\OrganizationalClimate\WorkConditionsHandler;
use App\Handlers\OrganizationalClimate\WorkDevelopmentCarrerAndRecognitionHandler;
use App\Handlers\OrganizationalClimate\WorkEngagementAndPrideHandler;
use App\Handlers\OrganizationalClimate\WorkLeadershipAndManagementHandler;
use App\Handlers\OrganizationalClimate\WorkMotivationValuesAndPurposesHandler;
use App\Handlers\OrganizationalClimate\WorkSocialRelationsHandler;
use App\Handlers\PsychosocialRisks\ManagementStyleHandler;
use App\Handlers\PsychosocialRisks\WorkContextHandler;
use App\Handlers\PsychosocialRisks\WorkExperienceHandler;
use App\Handlers\PsychosocialRisks\WorkProblemsHandler;
use App\Handlers\WellBeing\AnxietyTestHandler;
use App\Handlers\WellBeing\AutonomyTestHandler;
use App\Handlers\WellBeing\BurnoutTestHandler;
use App\Handlers\WellBeing\ConflictsTestHandler;
use App\Handlers\WellBeing\DepressionTestHandler;
use App\Handlers\WellBeing\EmotionalDemandsTestHandler;
use App\Handlers\WellBeing\InsecurityTestHandler;
use App\Handlers\WellBeing\PressureAtWorkTestHandler;
use App\Handlers\WellBeing\PressureForResultsTestHandler;
use App\Handlers\WellBeing\SocialRelationsTestHandler;
use App\Handlers\WellBeing\StressTestHandler;
use App\Models\Test;
use Illuminate\Contracts\Container\Container;

class TestHandlerFactory
{
    public function __construct(private Container $app) {}

    public function getHandler(Test $testInfo)
    {
        if (! $testInfo) {
            return $this->app->make(DefaultTestHandler::class);
        }

        return match ($testInfo->handler_type) {
            // psychosocial-risks
            'work-context' => $this->app->make(WorkContextHandler::class),
            'management-style' => $this->app->make(ManagementStyleHandler::class),
            'work-experiences' => $this->app->make(WorkExperienceHandler::class),
            'work-problems' => $this->app->make(WorkProblemsHandler::class),

            // well-being
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

            // organizational-climate
            'work-conditions' => $this->app->make(WorkConditionsHandler::class),
            'work-social-relations' => $this->app->make(WorkSocialRelationsHandler::class),
            'work-leadership-and-management' => $this->app->make(WorkLeadershipAndManagementHandler::class),
            'motivation-values-and-purposes' => $this->app->make(WorkMotivationValuesAndPurposesHandler::class),
            'development-carreer-recognition' => $this->app->make(WorkDevelopmentCarrerAndRecognitionHandler::class),
            'communication-and-information' => $this->app->make(WorkCommunicationAndInformationHandler::class),
            'engagement-and-pride' => $this->app->make(WorkEngagementAndPrideHandler::class),

            default => $this->app->make(DefaultTestHandler::class),
        };
    }
}
