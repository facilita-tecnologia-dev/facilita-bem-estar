<?php

namespace App\Handlers;

interface TestHandlerInterface
{
    /**
     * Processa as respostas de um teste específico
     *
     * @param array $answers As respostas do teste
     * @return array Resultado processado do teste
     */
    public function process(array $answers): array;
}