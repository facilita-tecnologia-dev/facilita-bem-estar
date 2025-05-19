<?php

namespace App\View\Composers;

use App\Helpers\AuthGuardHelper;
use App\Models\User;
use App\Services\User\UserElegibilityService;
use Illuminate\View\View;

class UserWelcomeComposer
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


            $view->with([
                'hasAnsweredPsychosocial' => $hasAnsweredPsychosocial,
                'hasAnsweredOrganizational' => $hasAnsweredOrganizational,
            ]);
        }
    }
}
