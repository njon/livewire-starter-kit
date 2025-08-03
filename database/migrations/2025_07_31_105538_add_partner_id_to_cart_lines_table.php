<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// In the migration file
    public function up()
    {
        Schema::table('lunar_cart_lines', function (Blueprint $table) {
        $table->unsignedBigInteger('owner_id')->nullable()->after('id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lunar_cart_lines', function (Blueprint $table) {
            $table->dropConstrainedForeignId('partner_id');
        });
    }
};
