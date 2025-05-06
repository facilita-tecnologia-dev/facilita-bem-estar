<?php

namespace App\Services\User;

use Illuminate\Database\Eloquent\Builder;

interface UserFilterInterface
{
    public function handle(Builder $query, \Closure $next) : Builder;
}