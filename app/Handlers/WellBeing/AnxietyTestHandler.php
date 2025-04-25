<?php

namespace App\Handlers\WellBeing;

use App\Enums\SeverityEnum;
use App\Handlers\TestHandlerInterface;

class AnxietyTestHandler implements TestHandlerInterface
{
    public function process(Test $testInfo, array $answers, $questions): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 15) {
            $severityTitle = 'Grave';
            $severityColor = SeverityEnum::CRITICO->value;
        } elseif ($totalPoints >= 10) {
            $severityTitle = 'Moderada';
            $severityColor = SeverityEnum::MEDIO->value;
        } elseif ($totalPoints >= 5) {
            $severityTitle = 'Leve';
            $severityColor = SeverityEnum::BAIXO->value;
        } else {
            $severityTitle = 'Mínima';
            $severityColor = SeverityEnum::MINIMO->value;
        }

        return [
            'severity_title' => $severityTitle,
            'severity_color' => $severityColor,
        ];
    }
}
