<?php

namespace App\Filters;

use App\Services\User\UserFilterInterface;
use Illuminate\Database\Eloquent\Builder;

class DepartmentFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (request()->filled('department') && !in_array('Todos', request('department'))) {
            $query->whereIn('department', request('department'));
        }

        return $next($query);
    }
}
