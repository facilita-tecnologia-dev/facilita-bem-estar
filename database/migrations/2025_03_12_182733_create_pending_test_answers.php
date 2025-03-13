<?php

use App\Models\QuestionOption;
use App\Models\TestQuestion;
use App\Models\TestType;
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
            $table->foreignIdFor(TestQuestion::class)->unique()->constrained();
            $table->foreignIdFor(TestType::class);
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
