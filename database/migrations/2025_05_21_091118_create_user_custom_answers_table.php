<?php

use App\Models\CustomQuestion;
use App\Models\CustomQuestionOption;
use App\Models\UserCustomTest;
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
        Schema::create('user_custom_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UserCustomTest::class);
            $table->foreignIdFor(CustomQuestion::class);
            $table->foreignIdFor(CustomQuestionOption::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_custom_answers');
    }
};
