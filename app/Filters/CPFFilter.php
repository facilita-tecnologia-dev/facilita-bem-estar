<?php

namespace App\Filters;

use App\Services\User\UserFilterInterface;
use Illuminate\Database\Eloquent\Builder;

class CPFFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (request()->filled('cpf')) {
            $query->where('cpf', 'like', '%'.request('cpf').'%');
        }

        return $next($query);
    }
}
