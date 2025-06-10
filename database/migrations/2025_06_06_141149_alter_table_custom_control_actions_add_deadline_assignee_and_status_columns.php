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
        Schema::table('custom_control_actions', function (Blueprint $table) {
            $table->string('assignee')->nullable();
            $table->string('deadline')->nullable();
            $table->string('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_control_actions', function (Blueprint $table) {
            $table->dropColumn('assignee');
            $table->dropColumn('deadline');
            $table->dropColumn('status');
        });
    }
};
