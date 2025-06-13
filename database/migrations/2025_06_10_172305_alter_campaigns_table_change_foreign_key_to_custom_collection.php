<?php

use App\Models\Collection;
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
        Schema::table('company_campaigns', function (Blueprint $table) {
            $table->dropColumn('collection_id');
            $table->foreignIdFor(CustomCollection::class, 'collection_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_campaigns', function (Blueprint $table) {
            $table->dropColumn('collection_id');
            $table->foreignIdFor(Collection::class, 'collection_id');
        });
    }
};
