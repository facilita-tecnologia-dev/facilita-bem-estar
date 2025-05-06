<?php

namespace App\Filters;

use App\Enums\AgeRangeEnum;
use App\Services\User\UserFilterInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class AgeRangeFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (request()->filled('age_range')) {
            assert(is_string(request('age_range')));

            [$min, $max] = match (request('age_range')) {
                AgeRangeEnum::YOUNG->value => [18, 25],
                AgeRangeEnum::EARLY_PROFESSIONAL->value => [26, 35],
                AgeRangeEnum::EXPERIENCIED->value => [36, 45],
                AgeRangeEnum::SENIOR->value => [46, 100],
                default => [18, 100],
            };

             $query->whereDate('birth_date', '<=', Carbon::now()->subYears($min))
            ->whereDate('birth_date', '>=', Carbon::now()->subYears($max + 1)->addDay());
        }

        return $next($query);
    }
}