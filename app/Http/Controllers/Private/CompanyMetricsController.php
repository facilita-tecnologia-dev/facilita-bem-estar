<?php

namespace App\Http\Controllers\Private;

use App\Models\CompanyMetric;
use App\Models\Metric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CompanyMetricsController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $metrics = CompanyMetric::where('company_id', session('company')->id)->with('metricType')->get()->keyBy('metricType.key_name');

        return view('private.company.company-metrics', compact('metrics'));
    }

    public function storeMetrics(Request $request)
    {
        $validatedData = $request->validate([
            'turnover' => 'nullable|between:0,100',
            'absenteeism' => 'nullable|between:0,100',
            'extra-hours' => 'nullable|between:0,100',
            'accidents' => 'nullable|between:0,100',
            'absences' => 'nullable|between:0,100',
        ]);

        DB::transaction(function () use ($validatedData) {
            $metrics = Metric::all();
            foreach ($validatedData as $key => $inputMetric) {

                if ($inputMetric == null) {
                    $inputMetric = 'null';
                }

                $metric = $metrics->where('key_name', $key)->first();

                CompanyMetric::updateOrInsert(
                    [
                        'company_id' => session('company')->id,
                        'metric_id' => $metric->id,
                    ],
                    [
                        'value' => $inputMetric,
                    ]
                );
            }
        });

        return back()->with('message', 'Indicadores armazenados com sucesso!');
    }
}
