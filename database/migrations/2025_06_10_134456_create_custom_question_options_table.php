<?php

use App\Models\CustomQuestion;
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
        Schema::create('custom_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CustomQuestion::class);
            $table->string('content');
            $table->integer('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_question_options');
    }
};
