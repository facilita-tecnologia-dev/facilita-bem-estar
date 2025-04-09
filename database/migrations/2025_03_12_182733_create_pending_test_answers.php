<?php

use App\Models\QuestionOption;
use App\Models\User;
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
        Schema::create('pending_test_answers', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->foreignIdFor(QuestionOption::class);
            $table->foreignIdFor(User::class);
            $table->foreignId('question_id')->unique()->constrained();
            $table->foreignId('test_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_test_answers');
    }
};
