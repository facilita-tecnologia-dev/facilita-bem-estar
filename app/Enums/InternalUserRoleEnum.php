<?php

namespace App\Enums;

enum InternalUserRoleEnum: string
{
    case EMPLOYEE = 'Colaborador';
    case INTERNAL_MANAGER = 'Gestor Interno';
}
