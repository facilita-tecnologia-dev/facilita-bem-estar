<?php

namespace App\View\Composers;

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
        $user = Auth::user();

        $hasAnsweredPsychosocial = $this->elegibilityService->hasAnsweredPsychosocialCollection($user);
        $hasAnsweredOrganizational = $this->elegibilityService->hasAnsweredOrganizationalCollection($user);

        $view->with([
            'hasAnsweredPsychosocial' => $hasAnsweredPsychosocial,
            'hasAnsweredOrganizational' => $hasAnsweredOrganizational,
        ]);
    }
}
