<?php

namespace App\RiskEvaluations;

use App\Models\Risk;

class RiskEvaluatorFactory
{
    public static function getRiskEvaluator(Risk $risk): ?RiskEvaluatorInterface
    {
        switch ($risk->name) {
            case 'Conflito de Papéis':
                return new ConflitoPapeis;
            case 'Rigidez Organizacional':
                return new RigidezOrganizacional;
            case 'Monotonia':
                return new Monotonia;
            case 'Imprevisibilidade':
                return new Imprevisibilidade;
            case 'Falta de Recursos':
                return new FaltaRecursos;
            case 'Sobrecarga de Trabalho':
                return new Sobrecarga;
            case 'Pressão Excessiva da Gestão':
                return new PressaoExcessiva;
            case 'Injustiça Percebida':
                return new InjusticaPercebida;
            case 'Falta de Suporte Gerencial':
                return new FaltaSuporte;
            case 'Conflitos com a Gestão':
                return new ConflitosGestao;
            case 'Falta de Reconhecimento':
                return new FaltaReconhecimento;
            case 'Gestão Individualista':
                return new GestaoIndividualista;
            case 'Dificuldade de Concentração':
                return new DificuldadeConcentracao;
            case 'Irritabilidade':
                return new Irritabilidade;
            case 'Frustração ou Desmotivação':
                return new Frustracao;
            case 'Isolamento Social':
                return new Isolamento;
            case 'Ansiedade ou Estresse':
                return new Ansiedade;
            case 'Esgotamento Emocional':
                return new Esgotamento;
            case 'Deterioração da Vida Pessoal':
                return new Deterioracao;
            case 'Problemas Psicossomáticos':
                return new Psicossomaticos;
            case 'Distúrbios do Sono':
                return new DisturbiosSono;
            case 'Afastamentos Frequentes':
                return new Afastamentos;
            case 'Distúrbios Psicológicos':
                return new DanosPsicologicos;
            case 'Distúrbios Físicos':
                return new DanosFisicos;
            default:
                return null;
        }
    }
}
