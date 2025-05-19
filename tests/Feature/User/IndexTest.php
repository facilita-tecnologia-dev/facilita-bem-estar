<?php

use App\Models\Company;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::first());
    session(['company' => Company::first()]);
});

it('show users page', function () {
    $response = $this->get(route('user.index'));

    $response->assertOk();
    $response->assertViewHasAll(['users', 'filtersApplied', 'filteredUserCount']);
});
