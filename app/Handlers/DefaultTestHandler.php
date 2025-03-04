<?php

namespace App\Handlers;

class DefaultTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        return [
            'answers' => $answers,
            'totalPoints' => array_sum($answers),
            'recommendations' => []
        ];
    }
}