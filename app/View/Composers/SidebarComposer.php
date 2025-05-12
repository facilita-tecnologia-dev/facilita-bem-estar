<?php

namespace App\View\Composers;

use App\Helpers\AuthGuardHelper;
use App\Models\Company;
use App\Models\User;
use App\Services\User\UserElegibilityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SidebarComposer
{
    protected UserElegibilityService $elegibilityService;

    public function __construct(UserElegibilityService $elegibilityService)
    {
        $this->elegibilityService = $elegibilityService;
    }

    public function compose(View $view): void
    {
        $user = AuthGuardHelper::user();

        if($user instanceof User){
            $hasAnsweredPsychosocial = $this->elegibilityService->hasAnsweredPsychosocialCollection($user);
            $hasAnsweredOrganizational = $this->elegibilityService->hasAnsweredOrganizationalCollection($user);
            $companiesToSwitch = array_map(fn($company) => [
                'option' => $company['name'],
                'value' => $company['id']
            ] ,$user->companies->toArray());

            $activeCampaigns = Company::firstWhere('id', session('company')->id)
            ->campaigns()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('end_date', '>=', now());

            $hasActivePsychosocialCampaign = $activeCampaigns->where('collection_id', 1)->exists();
            $hasActiveOrganizationalCampaign = $activeCampaigns->where('collection_id', 2)->exists();
            
            $view->with([
                'hasAnsweredPsychosocial' => $hasAnsweredPsychosocial,
                'hasAnsweredOrganizational' => $hasAnsweredOrganizational,
                'companiesToSwitch' => $companiesToSwitch,
                'hasActivePsychosocialCampaign' => $hasActivePsychosocialCampaign,
                'hasActiveOrganizationalCampaign' => $hasActiveOrganizationalCampaign,
            ]);
        }
    }
}
