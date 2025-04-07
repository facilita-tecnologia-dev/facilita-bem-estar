<?php

namespace App\RiskEvaluations;

use App\Handlers\TestHandlerInterface;
use App\Handlers\WorkContextHandler;
use App\Models\Risk;
use App\Models\TestType;
use App\Repositories\TestRepository;

class RiskEvaluatorFactory
{
    public function getRiskEvaluator(Risk $risk): RiskEvaluatorInterface | null
    {   
        switch ($risk->name) {
            case 'Conflito de Papéis':
                return new ConflitoPapeis();
            case 'Rigidez Organizacional':
                return new RigidezOrganizacional();
            case 'Monotonia':
                return new Monotonia();
            case 'Imprevisibilidade':
                return new Imprevisibilidade();
            case 'Falta de Recursos':
                return new FaltaRecursos();
            case 'Sobrecarga de Trabalho':
                return new Sobrecarga();
            case 'Pressão Excessiva da Gestão':
                return new PressaoExcessiva();
            case 'Injustiça Percebida':
                return new InjusticaPercebida();
            case 'Falta de Suporte Gerencial':
                return new FaltaSuporte();
            case 'Conflitos com a Gestão':
                return new ConflitosGestao();
            case 'Falta de Reconhecimento':
                return new FaltaReconhecimento();
            case 'Gestão Individualista':
                return new GestaoIndividualista();
            case 'Dificuldade de Concentração':
                return new DificuldadeConcentracao();
            case 'Irritabilidade':
                return new Irritabilidade();
            case 'Frustração ou Desmotivação':
                return new Frustracao();
            case 'Isolamento Social':
                return new Isolamento();
            case 'Ansiedade ou Estresse':
                return new Ansiedade();
            case 'Esgotamento Emocional':
                return new Esgotamento();
            case 'Deterioração da Vida Pessoal':
                return new Deterioracao();
            case 'Problemas Psicossomáticos':
                return new Deterioracao();
            case 'Distúrbios do Sono':
                return new DisturbiosSono();
            default:
                return null;
        }
    }
}