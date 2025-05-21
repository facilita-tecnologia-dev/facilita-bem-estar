<?php

use App\Models\CustomTest;
use App\Models\UserCollection;
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
        Schema::create('user_custom_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UserCollection::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(CustomTest::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_custom_tests');
    }
};
