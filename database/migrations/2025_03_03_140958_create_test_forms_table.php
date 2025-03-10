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
        Schema::create('test_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('test_collection_id');
            $table->string('test_name');
            $table->integer('total_points');
            $table->string('severity_title');
            $table->string('severity_color');
            $table->string('recommendation');
            $table->timestamps();

            $table->foreign('test_collection_id')->references('id')->on('test_collections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_forms');
    }
};
