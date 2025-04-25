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
            $table->string('marital_status')->nullable()->change();
            $table->string('work_shift')->nullable()->change();
            $table->string('education_level')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('marital_status')->nullable(false)->change();
            $table->string('work_shift')->nullable(false)->change();
            $table->string('education_level')->nullable(false)->change();
        });
    }
};
