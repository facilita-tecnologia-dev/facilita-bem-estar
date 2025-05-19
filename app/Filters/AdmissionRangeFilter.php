<?php

namespace App\Filters;

use App\Enums\AdmissionRangeEnum;
use App\Services\User\UserFilterInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class AdmissionRangeFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (request()->filled('admission_range')) {
            assert(is_string(request('admission_range')));

            [$min, $max] = match (request('admission_range')) {
                AdmissionRangeEnum::NEW_EMPLOYEE->value => [0, 1],
                AdmissionRangeEnum::EARLY_EMPLOYEE->value => [2, 4],
                AdmissionRangeEnum::ESTABLISHED_EMPLOYEE->value => [5, 10],
                AdmissionRangeEnum::VETERAN_EMPLOYEE->value => [11, 100],
                default => [0, 100],
            };

            $query->whereDate('admission', '<=', Carbon::now()->subYears($min))
                ->whereDate('admission', '>=', Carbon::now()->subYears($max + 1)->addDay());
        }

        return $next($query);
    }
}
