<?php

namespace App\Handlers;

class DefaultTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        return [
            'answers' => $answers,
            'total_points' => array_sum($answers),
            'recommendations' => []
        ];
    }
}