<?php

namespace App\Filters;

use App\Enums\AdmissionRangeEnum;
use App\Services\User\UserFilterInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class HasAnsweredOrganizationalFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (request()->filled('has_answered_organizational')) {
            if(request('has_answered_organizational') == 'Realizado'){
                $query->whereHas('latestOrganizationalClimateCollection', function ($query) {
                    $query->whereYear('created_at', Carbon::now()->year);
                });
            } else{
                $query->whereDoesntHave('latestOrganizationalClimateCollection', function ($query) {
                    $query->whereYear('created_at', Carbon::now()->year);
                });
            }
        }

        return $next($query);
    }
}