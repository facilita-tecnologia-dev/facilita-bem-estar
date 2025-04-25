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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('department')->nullable()->change();
            $table->string('logo')->nullable()->change();
            $table->dropColumn(['i_turnover', 'i_absenteeism', 'i_overtime', 'i_accidents', 'i_absence']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('department')->change();
            $table->string('logo')->change();
        });
    }
};
