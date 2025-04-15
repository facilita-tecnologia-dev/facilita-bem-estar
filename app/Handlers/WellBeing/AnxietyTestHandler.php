<?php

namespace App\Handlers\WellBeing;

use App\Handlers\TestHandlerInterface;

class AnxietyTestHandler implements TestHandlerInterface
{
    public function process(Test $testInfo, array $answers, $questions): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 15) {
            $severityTitle = 'Grave';
            $severityColor = 5;
        } elseif ($totalPoints >= 10) {
            $severityTitle = 'Moderada';
            $severityColor = 3;
        } elseif ($totalPoints >= 5) {
            $severityTitle = 'Leve';
            $severityColor = 2;
        } else {
            $severityTitle = 'Mínima';
            $severityColor = 1;
        }

        return [
            'severity_title' => $severityTitle,
            'severity_color' => $severityColor,
        ];
    }
}
