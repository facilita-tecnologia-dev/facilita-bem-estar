<?php

namespace App\Handlers;

use App\Models\Test;

class DefaultTestHandler implements TestHandlerInterface
{
    public function process(Test $testInfo, array $answers, $questions): array
    {
        return [
            'answers' => $answers,
            'total_points' => array_sum($answers),
            'recommendations' => [],
        ];
    }
}
