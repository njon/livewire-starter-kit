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
        Schema::table('lunar_prices', function (Blueprint $table) {
            $table->decimal('tax_rate', 5, 2)->nullable()->after('compare_price')->default(null)->comment('Tax rate percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lunar_prices', function (Blueprint $table) {
            $table->dropColumn('tax_rate');
        });
    }
};