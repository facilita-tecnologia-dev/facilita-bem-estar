<?php

use App\Models\Company;
use App\Models\Metric;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Metric::class);
            $table->foreignIdFor(Company::class);
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_metrics');
    }
};
