<?php

namespace App\Enums;

enum DepartmentEnum: string
{
    case FINANCEIRO = 'Financeiro';
    case PRODUCAO = 'Produção';
    case ENGENHARIA = 'Engenharia';
    case MARKETING = 'Marketing';
    case RH = 'RH';
    case TI = 'TI';
}
