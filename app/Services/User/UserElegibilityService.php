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
        return session('company')
            ->campaigns()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get()
            ->groupBy('collection.collection_id');
    }

    public function hasActivePsychosocialCampaign(): bool
    {
        $activeCampaigns = $this->activeCompanyCampaigns();

        return isset($activeCampaigns[1]);
    }

    public function hasActiveOrganizationalCampaign(): bool
    {
        $activeCampaigns = $this->activeCompanyCampaigns();

        return isset($activeCampaigns[2]);
    }
}
