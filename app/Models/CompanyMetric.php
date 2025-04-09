<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyMetric extends Model
{
    public $timestamps = false;

    protected $table = 'company_metrics';

    /**
     * Returns the company to which this company metric belongs.
     * @return BelongsTo
     */
    public function parentCompany(): BelongsTo {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Returns the base metric of this company metric.
     * @return BelongsTo
     */
    public function metricType(): BelongsTo {
        return $this->belongsTo(Metric::class, 'metric_id');
    }
}
