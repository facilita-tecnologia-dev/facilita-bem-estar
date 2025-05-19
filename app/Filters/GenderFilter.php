<?php

namespace App\Filters;

use App\Services\User\UserFilterInterface;
use Illuminate\Database\Eloquent\Builder;

class GenderFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (request()->filled('gender')) {
            $query->where('gender', request('gender'));
        }

        return $next($query);
    }
}
