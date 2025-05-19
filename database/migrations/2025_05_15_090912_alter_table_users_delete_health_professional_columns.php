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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('photo');
            $table->dropColumn('email');
            $table->dropColumn('years_of_experience');
            $table->dropColumn('appointment_price');
            $table->dropColumn('appointment_duration');
            $table->dropColumn('resume');
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable();
            $table->string('email')->unique()->nullable();
            $table->unsignedTinyInteger('years_of_experience')->nullable();
            $table->smallInteger('appointment_price')->nullable();
            $table->smallInteger('appointment_duration')->nullable();
            $table->string('resume')->nullable();
            $table->text('description')->nullable();
        });
    }
};
