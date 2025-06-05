<?php

namespace App\Enums;

enum ProbabilityEnum: int
{
    case IMPROVAVEL = 1;
    case POSSIVEL = 2;
    case PROVAVEL = 3;
    case MUITO_PROVAVEL = 4;

    public function label(): string
    {
        return match ($this) {
            self::IMPROVAVEL => 'Improvável',
            self::POSSIVEL => 'Possível',
            self::PROVAVEL => 'Provável',
            self::MUITO_PROVAVEL => 'Muito Provável',
        };
    }

    public static function labelFromValue(int $value): ?string
    {
        return self::tryFrom($value)?->label();
    }
}
