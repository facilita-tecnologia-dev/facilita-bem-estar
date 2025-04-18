<?php

use App\Models\Collection;
use App\Models\TestType;
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
        Schema::create('user_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_collection_id')->constrained();
            $table->foreignId('test_id')->constrained();
            $table->string('score');
            $table->string('severity_title');
            $table->string('severity_color');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tests');
    }
};
