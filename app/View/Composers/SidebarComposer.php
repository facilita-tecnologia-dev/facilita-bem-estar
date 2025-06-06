<?php

namespace App\View\Composers;

use App\Helpers\AuthGuardHelper;
use App\Models\User;
use App\Repositories\TestRepository;
use App\Services\User\UserElegibilityService;
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
        
        if ($user instanceof User) {
            $hasAnsweredPsychosocial = $this->elegibilityService->hasAnsweredPsychosocialCollection($user);
            $hasAnsweredOrganizational = $this->elegibilityService->hasAnsweredOrganizationalCollection($user);
            $companiesToSwitch = array_map(fn ($company) => [
                'name' => $company['name'],
                'id' => $company['id'],
            ], $user->companies->toArray());

            $hasActivePsychosocialCampaign = $this->elegibilityService->hasActivePsychosocialCampaign();
            $hasActiveOrganizationalCampaign = $this->elegibilityService->hasActiveOrganizationalCampaign();

            $view->with([
                'hasAnsweredPsychosocial' => $hasAnsweredPsychosocial,
                'hasAnsweredOrganizational' => $hasAnsweredOrganizational,
                'companiesToSwitch' => $companiesToSwitch,
                'hasActivePsychosocialCampaign' => $hasActivePsychosocialCampaign,
                'hasActiveOrganizationalCampaign' => $hasActiveOrganizationalCampaign,
                'isInstanceOfUser' => true,
            ]);
        }
    }
}
