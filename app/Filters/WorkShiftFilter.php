<?php

namespace App\Filters;

use App\Services\User\UserFilterInterface;
use Illuminate\Database\Eloquent\Builder;

class WorkShiftFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (request()->filled('work_shift')) {
            $query->where('work_shift', request('work_shift'));
        }

        return $next($query);
    }
}