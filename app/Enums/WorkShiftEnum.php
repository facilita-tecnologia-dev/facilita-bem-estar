<?php

namespace App\Enums;

enum WorkShiftEnum: string
{
    case DAY = 'Diurno';
    case NIGHT = 'Noturno';
    case THIRD = '3º turno';
}
