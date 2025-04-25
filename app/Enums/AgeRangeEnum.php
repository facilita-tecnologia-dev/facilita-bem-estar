<?php

namespace App\Enums;

enum AgeRangeEnum: string
{
    case YOUNG = '18-25';
    case EARLY_PROFESSIONAL = '26-35';
    case EXPERIENCIED = '36-45';
    case SENIOR = '45+';
}
