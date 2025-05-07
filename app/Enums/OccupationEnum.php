<?php

namespace App\Enums;

enum OccupationEnum: string
{
    // Financeiro
    case ANALISTA_FINANCEIRO = 'Analista Financeiro';
    case CONTADOR = 'Contador';
    case TESOUREIRO = 'Tesoureiro';

    // Produção
    case OPERADOR_MAQUINA = 'Operador de Máquina';
    case SUPERVISOR_PRODUCAO = 'Supervisor de Produção';
    case ENCARREGADO_LINHA = 'Encarregado de Linha';

    // Engenharia
    case ENGENHEIRO_PRODUTO = 'Engenheiro de Produto';
    case ENGENHEIRO_PROCESSOS = 'Engenheiro de Processos';
    case TECNICO_PROJETOS = 'Técnico de Projetos';

    // Marketing
    case ANALISTA_MARKETING = 'Analista de Marketing';
    case COORDENADOR_CAMPANHAS = 'Coordenador de Campanhas';
    case DESIGNER_GRAFICO = 'Designer Gráfico';

    // RH
    case ANALISTA_RH = 'Analista de RH';
    case RECRUTADOR = 'Recrutador';
    case GERENTE_PESSOAL = 'Gerente de Pessoal';

    // TI
    case DESENVOLVEDOR_BACKEND = 'Desenvolvedor Backend';
    case ANALISTA_SISTEMAS = 'Analista de Sistemas';
    case SUPORTE_TI = 'Suporte Técnico';
}
