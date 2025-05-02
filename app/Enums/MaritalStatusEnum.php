<?php

namespace App\Enums;

enum MaritalStatusEnum: string
{
    case SINGLE = 'Solteiro(a)';
    case MARRIED = 'Casado(a)';
    case WIDOWED = 'Viúvo(a)';
    case DIVORCED = 'Divorciado(a)';
}
