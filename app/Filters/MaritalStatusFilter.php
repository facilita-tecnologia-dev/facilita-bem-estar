<?php

namespace App\Filters;

use App\Services\User\UserFilterInterface;
use Illuminate\Database\Eloquent\Builder;

class MaritalStatusFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (request()->filled('marital_status')) {
            $query->where('marital_status', request('marital_status'));
        }

        return $next($query);
    }
}