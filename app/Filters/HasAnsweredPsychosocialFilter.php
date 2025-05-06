<?php

namespace App\Filters;

use App\Enums\AdmissionRangeEnum;
use App\Services\User\UserFilterInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class HasAnsweredPsychosocialFilter implements UserFilterInterface
{
    public function handle(Builder $query, \Closure $next): Builder
    {
        if (request()->filled('has_answered_psychosocial')) {
            if(request('has_answered_psychosocial') == 'Realizado'){
                $query->whereHas('latestPsychosocialCollection', function ($query) {
                    $query->whereYear('created_at', Carbon::now()->year);
                });
            } else{
                $query->whereDoesntHave('latestPsychosocialCollection', function ($query) {
                    $query->whereYear('created_at', Carbon::now()->year);
                });
            }
        }

        return $next($query);
    }
}