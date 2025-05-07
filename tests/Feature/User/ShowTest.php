<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->actingAs(User::first());
    session(['company' => Company::first()]);
});


it('show user page', function(){
    $response = $this->get(route('user.show', User::first()));

    $response->assertOk();
    $response->assertViewIs('private.users.show');
    $response->assertViewHas('user', fn(User $user) => $user->name == User::first()->name);
    $response->assertViewHas('latestPsychosocialCollectionDate', function($latestPsychosocialCollectionDate) {
        return $latestPsychosocialCollectionDate == User::first()['latestPsychosocialCollection']?->created_at->diffForHumans() ?? 'Nunca';
    });
    $response->assertViewHas('latestOrganizationalClimateCollectionDate', function($latestOrganizationalClimateCollectionDate) {
        return $latestOrganizationalClimateCollectionDate == User::first()['latestOrganizationalClimateCollection']?->created_at->diffForHumans() ?? 'Nunca';
    });
});
