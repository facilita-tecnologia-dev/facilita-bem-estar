<?php

namespace App\Enums;

enum AdmissionRangeEnum: string
{
    case NEW_EMPLOYEE = '0-1';
    case EARLY_EMPLOYEE = '2-4';
    case ESTABLISHED_EMPLOYEE = '5-10';
    case VETERAN_EMPLOYEE = '10+';
}
