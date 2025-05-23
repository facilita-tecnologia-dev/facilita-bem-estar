<?php

use App\Models\Company;
use App\Models\Permission;
use App\Models\User;
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
        Schema::create('user_custom_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Company::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Permission::class)->constrained()->onDelete('cascade');
            $table->boolean('allowed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_custom_permissions');
    }
};
