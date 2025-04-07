<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyMetric extends Model
{
    public $timestamps = false;


    public function metricType(){
        return $this->belongsTo(Metric::class, 'metric_id');
    }
}
