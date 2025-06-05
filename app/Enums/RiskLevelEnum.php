<?php

namespace App\Enums;

enum RiskLevelEnum: int
{
    case RISCO_BAIXO = 1;
    case RISCO_MODERADO = 2;
    case RISCO_ALTO = 3;
    case RISCO_CRITICO = 4;

    public function label(): string
    {
        return match ($this) {
            self::RISCO_BAIXO => 'Risco Baixo',
            self::RISCO_MODERADO => 'Risco Moderado',
            self::RISCO_ALTO => 'Risco Alto',
            self::RISCO_CRITICO => 'Risco Crítico',
        };
    }

    public static function labelFromValue(int $value): ?string
    {
        return self::tryFrom($value)?->label();
    }
}
