<?php

use App\Models\CustomCollection;
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
            $table->foreignIdFor(CustomCollection::class);
            $table->string('display_name');
            $table->string('statement')->nullable();
            $table->integer('order');
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
