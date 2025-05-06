<?php

namespace App\Filters;

use App\Services\User\UserFilterInterface;
use Illuminate\Database\Eloquent\Builder;

class OccupationFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (request()->filled('occupation')) {
            $query->where('occupation', request('occupation'));
        }

        return $next($query);
    }
}