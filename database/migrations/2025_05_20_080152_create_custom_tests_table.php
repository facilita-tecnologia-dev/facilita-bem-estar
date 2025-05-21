<?php

use App\Models\Collection;
use App\Models\Company;
use App\Models\Test;
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
        Schema::create('custom_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Collection::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Test::class)->nullable();
            $table->string('key_name');
            $table->string('display_name');
            $table->string('statement');
            $table->string('reference');
            $table->integer('number_of_questions');
            $table->integer('order');
            $table->boolean('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_tests');
    }
};
