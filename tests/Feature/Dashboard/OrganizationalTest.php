<?php

use App\Models\Collection;
use App\Models\Company;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::first());
    session(['company' => Company::first()]);
});

it('should be able to see Organizational Dashboard', function () {
    $response = $this->get(route('dashboard.organizational-climate'));

    $response->assertOk();
    $response->assertViewHasAll(['organizationalClimateResults', 'organizationalTestsParticipation', 'filtersApplied', 'filteredUserCount']);
});

it('should be able to generate report of Organizational Dashboard', function () {
    $response = $this->post(route('dashboard.organizational-climate.report'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/pdf');
});

it('should be able to see Organizational Answers', function () {
    $response = $this->get(route('dashboard.organizational-climate.by-answers'));

    $response->assertOk();
    $response->assertViewHasAll(['organizationalClimateResults']);
});

it('should be able to see Organizational Answers with filtered test', function () {
    $collection = Collection::where('key_name', 'organizational-climate')->first();
    $tests = $collection->tests;

    foreach ($tests as $test) {
        $response = $this->get(route('dashboard.organizational-climate.by-answers', ['test' => $test->display_name]));

        $response->assertOk();
        $response->assertViewHasAll(['organizationalClimateResults']);
    }
});

it('should be able to generate report of Organizational Answers', function () {
    $response = $this->get(route('dashboard.organizational-climate.by-answers.report'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/pdf');
});
