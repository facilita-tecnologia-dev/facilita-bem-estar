<?php

use App\Models\CustomTest;
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
        Schema::create('custom_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CustomTest::class);
            $table->string('statement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_questions');
    }
};
