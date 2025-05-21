<?php

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
        Schema::table('user_tests', function (Blueprint $table) {
            $table->dropColumn('score');
            $table->dropColumn('severity_title');
            $table->dropColumn('severity_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_tests', function (Blueprint $table) {
            $table->string('score');
            $table->string('severity_title');
            $table->string('severity_color');
        });
    }
};
