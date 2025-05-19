<?php

use App\Models\Collection;
use App\Models\Company;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::first());
    session(['company' => Company::first()]);
});

it('should be able to see Psychosocial Dashboard', function () {
    $response = $this->get(route('dashboard.psychosocial'));

    $response->assertOk();
    $response->assertViewHasAll(['psychosocialRiskResults', 'psychosocialTestsParticipation', 'filtersApplied', 'filteredUserCount']);
});

it('should be able to see Psychosocial By Department', function () {
    $collection = Collection::where('key_name', 'psychosocial-risks')->first();
    $tests = $collection->tests;

    foreach ($tests as $test) {
        $response = $this->get(route('dashboard.psychosocial.by-department', $test->display_name));

        $response->assertOk();
        $response->assertViewHas('testName', fn (string $testName) => $testName === $test->display_name);
        $response->assertViewHas('resultsPerDepartment');
    }
});

it('should be able to see Psychosocial Results List', function () {
    $collection = Collection::where('key_name', 'psychosocial-risks')->first();
    $tests = $collection->tests;

    foreach ($tests as $test) {
        $response = $this->get(route('dashboard.psychosocial.list', $test->display_name));

        $response->assertOk();
        $response->assertViewHas('testName', fn (string $testName) => $testName === $test->display_name);
        $response->assertViewHasAll(['usersList', 'pageData', 'filtersApplied', 'filteredUserCount']);
    }
});

it('should be able to see Psychosocial Risks', function () {
    $response = $this->get(route('dashboard.psychosocial.risks'));

    $response->assertOk();
    $response->assertViewHas('risks');
});

it('should be able to generate a risks report', function () {
    $response = $this->get(route('dashboard.psychosocial.risks.pdf'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/pdf');
});
