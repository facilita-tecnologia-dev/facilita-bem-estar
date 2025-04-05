<?php

use App\Models\TestForm;
use App\Models\TestQuestion;
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
        Schema::table('test_answers', function (Blueprint $table) {
            $table->foreignIdFor(TestQuestion::class);
            $table->foreignIdFor(TestForm::class);
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_answers', function (Blueprint $table) {
            $table->dropColumn('test_question_id'); 
        });
    }
};
