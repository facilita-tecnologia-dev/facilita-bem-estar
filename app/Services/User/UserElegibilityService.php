<?php

namespace App\Services\User;

use App\Models\Company;
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

    private function activeCompanyCampaigns()
    {
        return Company::firstWhere('id', session('company')->id)
            ->campaigns()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function hasActivePsychosocialCampaign(): bool
    {
        $activeCampaigns = $this->activeCompanyCampaigns();

        return $activeCampaigns->where('collection_id', 1)->exists();
    }

    public function hasActiveOrganizationalCampaign(): bool
    {
        $activeCampaigns = $this->activeCompanyCampaigns();

        return $activeCampaigns->where('collection_id', 2)->exists();
    }
}
