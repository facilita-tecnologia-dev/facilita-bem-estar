<?php

namespace App\Http\Controllers\Private;

use Illuminate\Http\Request;

class WelcomeController
{
    public function welcomeCompany()
    {
        $totalActions = 5;
        $neededActionsCount = 0;

        $noCompanyLogo = !session('company')->logo;
        $noCompanyUsers = !session('company')->users->count();
        $noCompanyManager = !session('company')->users->filter(fn($user)=> $user->hasRole('manager'))->count();
        $noCompanyMetrics = !session('company')->metrics()->where('value')->exists();
        $noCompanyCampaigns = !session('company')->campaigns()->exists();

        if($noCompanyLogo){$neededActionsCount++;}
        if($noCompanyUsers){$neededActionsCount++;}
        if($noCompanyManager){$neededActionsCount++;}
        if($noCompanyMetrics){$neededActionsCount++;}
        if($noCompanyCampaigns){$neededActionsCount++;}

        return view('private.welcome.company', [
            'totalActions' => $totalActions,
            'neededActionsCount' => $neededActionsCount,
            'noCompanyLogo' => $noCompanyLogo,
            'noCompanyUsers' => $noCompanyUsers,
            'noCompanyManager' => $noCompanyManager,
            'noCompanyMetrics' => $noCompanyMetrics,
            'noCompanyCampaigns' => $noCompanyCampaigns,
        ]);
    }

    public function welcomeUser()
    {
        return view('private.welcome.user');
    }

}
