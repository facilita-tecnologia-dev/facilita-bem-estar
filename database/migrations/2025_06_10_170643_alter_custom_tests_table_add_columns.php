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
        // Schema::table('custom_tests', function (Blueprint $table) {
        //     $table->string('key_name')->nullable();
        //     $table->string('reference')->nullable();
        //     $table->string('number_of_questions')->nullable();
        //     $table->string('handler_type')->nullable();
        //     $table->text('statement')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_tests', function (Blueprint $table) {
            $table->dropColumn('key_name');
            $table->dropColumn('reference');
            $table->dropColumn('number_of_questions');
            $table->dropColumn('handler_type');
        });
    }
};
