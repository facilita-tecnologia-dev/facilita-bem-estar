<?php

namespace App\Enums;

enum EmployeeStatusEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Ativo',
            self::INACTIVE => 'Inativo',
        };
    }

    public static function labelFromValue(int $value): ?string
    {
        return self::tryFrom($value)?->label();
    }
}
