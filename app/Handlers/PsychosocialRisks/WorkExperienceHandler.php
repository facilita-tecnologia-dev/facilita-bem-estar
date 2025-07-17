<?php

namespace App\Handlers\PsychosocialRisks;

use App\Enums\ProbabilityEnum;
use App\Enums\RiskSeverityEnum;
use App\Enums\SeverityEnum;
use App\Models\CustomTest;
use App\Models\Risk;
use App\Models\Test;
use App\Models\UserTest;
use Illuminate\Support\Collection;
use App\Models\UserCustomTest;
use App\Services\RiskService;

class WorkExperienceHandler
{
    public function processTests(Test $testType, Collection $metrics): array
    {
        $risksList = RiskService::evaluateRisks($testType, $metrics);

        return [
            'risks' => $risksList,
        ];
    }

    public function processIndividualTest(Test $testType, UserTest $userTest, Collection $metrics): array
    {
        $risksList = RiskService::evaluateIndividualTestRisks($testType, $userTest, $metrics);
        
        return [
            'risks' => $risksList,
        ];
    }
}
