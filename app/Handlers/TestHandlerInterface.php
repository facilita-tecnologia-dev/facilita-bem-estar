<?php

namespace App\Handlers;

use App\Models\Test;

interface TestHandlerInterface
{
    /**
     * @param  array  $answers  Test Answers
     * @return array Test Result
     */
    public function process(Test $testInfo, array $answers, $questions): array;
}
