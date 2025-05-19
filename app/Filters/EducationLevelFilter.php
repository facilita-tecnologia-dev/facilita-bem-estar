<?php

namespace App\Filters;

use App\Services\User\UserFilterInterface;
use Illuminate\Database\Eloquent\Builder;

class EducationLevelFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (request()->filled('education_level')) {
            $query->where('education_level', request('education_level'));
        }

        return $next($query);
    }
}
