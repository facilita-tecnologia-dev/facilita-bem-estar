<?php

namespace App\Repositories;

class TestRepository
{
    protected $tests = [
        'ansiedade' => [
            'displayName' => 'Teste de Ansiedade',
            'nextTest' => 'depressao',
            'numberOfQuestions' => 7,
            'handlerType' => 'anxiety'
        ],
        'depressao' => [
            'displayName' => 'Teste de Depressão',
            'nextTest' => '',
            'numberOfQuestions' => 9,
            'handlerType' => 'depression'
        ],
    ];

    public function exists(string $test){
        return isset($this->tests[$test]);
    }

    public function getTestInfo(string $test){
        return $this->tests[$test] ?? null;
    }

    public function getAllTests(){
        return $this->tests;
    }

    public function getNextTest(string $currentTest){
        return $this->tests[$currentTest]['nextTest'] ?? null;
    }
}