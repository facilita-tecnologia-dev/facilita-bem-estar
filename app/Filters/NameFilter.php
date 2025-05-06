<?php

namespace App\Filters;

use App\Services\User\UserFilterInterface;
use Illuminate\Database\Eloquent\Builder;

class NameFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (request()->filled('name')) {
            $query->where('name', 'like', '%' . request('name') . '%');
        }

        return $next($query);
    }
}