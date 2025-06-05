<?php

namespace App\Enums;

enum RiskSeverityEnum: int
{
    case BAIXA = 1;
    case MODERADA = 2;
    case ALTA = 3;
    case CRITICA = 4;

    public function label(): string
    {
        return match ($this) {
            self::BAIXA => 'Baixa',
            self::MODERADA => 'Moderada',
            self::ALTA => 'Alta',
            self::CRITICA => 'Crítica',
        };
    }

    public static function labelFromValue(int $value): ?string
    {
        return self::tryFrom($value)?->label();
    }
}
