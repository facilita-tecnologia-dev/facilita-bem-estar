<?php

namespace App\Services\User;

use App\Enums\AdmissionRangeEnum;
use App\Enums\AgeRangeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class UserFilterService
{
    public function applyAgeRange(Builder $query, ?string $range): Builder
    {
        if (! $range) {
            return $query;
        }

        [$min, $max] = match ($range) {
            AgeRangeEnum::YOUNG->value => [18, 25],
            AgeRangeEnum::EARLY_PROFESSIONAL->value => [26, 35],
            AgeRangeEnum::EXPERIENCIED->value => [36, 45],
            AgeRangeEnum::SENIOR->value => [46, 100],
        };

        return $query->whereDate('birth_date', '<=', Carbon::now()->subYears($min))
            ->whereDate('birth_date', '>=', Carbon::now()->subYears($max + 1)->addDay());
    }

    public function applyAdmissionRange(Builder $query, ?string $range): Builder
    {
        if (! $range) {
            return $query;
        }

        [$min, $max] = match ($range) {
            AdmissionRangeEnum::NEW_EMPLOYEE->value => [0, 1],
            AdmissionRangeEnum::EARLY_EMPLOYEE->value => [2, 4],
            AdmissionRangeEnum::ESTABLISHED_EMPLOYEE->value => [5, 10],
            AdmissionRangeEnum::VETERAN_EMPLOYEE->value => [11, 100],
        };

        return $query->whereDate('admission', '<=', Carbon::now()->subYears($min))
            ->whereDate('admission', '>=', Carbon::now()->subYears($max + 1)->addDay());
    }
}
