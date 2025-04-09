<?php

namespace App\Handlers;

interface TestHandlerInterface
{
    /**
     * @param  array  $answers  Test Answers
     * @return array Test Result
     */
    public function process(array $answers, $testInfo): array;
}
