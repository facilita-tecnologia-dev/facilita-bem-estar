<?php

use App\Models\Company;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::first());
    session(['company' => Company::first()]);
});

it('should be able to see company metrics page', function () {
    $response = $this->get(route('company-metrics'));

    $response->assertOk();
    $response->assertViewHas('metrics', function ($metrics) {
        return $metrics->every(fn ($metric) => $metric->company_id === session('company')->id);
    });
});

it('should be able to update company metrics', function () {
    $response = $this->post(route('company-metrics'), [
        'turnover' => '10',
        'absenteeism' => '15',
        'extra-hours' => '6',
        'accidents' => '6',
        'absences' => '4',
    ]);

    $this->assertDatabaseHas('company_metrics', [
        'company_id' => session('company')->id,
        'metric_id' => 1,
        'value' => 10,
    ]);
});
