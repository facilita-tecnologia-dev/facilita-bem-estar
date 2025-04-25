<?php

namespace App\Enums;

enum SeverityEnum: string
{
    case MINIMO = 'MINIMO';
    case BAIXO = 'BAIXO';
    case MEDIO = 'MEDIO';
    case ALTO = 'ALTO';
    case CRITICO = 'CRITICO';
}
