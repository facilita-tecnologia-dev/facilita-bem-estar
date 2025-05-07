<?php

use App\Models\Collection;
use App\Models\Company;
use App\Models\Test;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::first());
    session(['company' => Company::first()]);
});

it('should be able to see Demographics Dashboard', function(){
    $response = $this->get(route('dashboard.demographics'));

    $response->assertOk();
    $response->assertViewHasAll(['companyMetrics', 'demographics']);
});

