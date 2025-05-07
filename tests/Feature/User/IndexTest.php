<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->actingAs(User::first());
    session(['company' => Company::first()]);
});


it('show user page', function(){
    $response = $this->get(route('user.index'));

    $response->assertOk();
    $response->assertViewIs('private.users.index');
    $response->assertViewHasAll(['users', 'filtersApplied', 'filteredUserCount']);
});
