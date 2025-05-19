<?php

namespace App\Filters;

use App\Helpers\AuthGuardHelper;
use App\Services\User\UserFilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DepartmentScopeFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (Auth::guard('user')->check()) {
            /** @var User $user */
            $user = AuthGuardHelper::user();
            $authUserDepartmentScopes = $user->departmentScopes()->where('allowed', true)->pluck('department');

            $query->whereIn('department', $authUserDepartmentScopes);
        }

        return $next($query);
    }
}
