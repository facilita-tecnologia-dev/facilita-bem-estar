<?php

use App\Models\Company;
use App\Models\ControlAction;
use App\Models\Risk;
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
        Schema::create('custom_control_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class);
            $table->foreignIdFor(ControlAction::class)->nullable();
            $table->foreignIdFor(Risk::class);
            $table->string('content');
            $table->boolean('allowed')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_control_actions');
    }
};
