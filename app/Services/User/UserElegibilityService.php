<?php

namespace App\Services\User;

use App\Models\User;
use Carbon\Carbon;

class UserElegibilityService
{
    public function hasAnsweredThisYear(?Carbon $date): bool
    {
        return $date?->year === now()->year;
    }

    public function hasAnsweredPsychosocialCollection(User $user): bool
    {
        return $this->hasAnsweredThisYear($user['latestPsychosocialCollection']?->created_at);
    }

    public function hasAnsweredOrganizationalCollection(User $user): bool
    {
        return $this->hasAnsweredThisYear($user['latestOrganizationalClimateCollection']?->created_at);
    }
}
