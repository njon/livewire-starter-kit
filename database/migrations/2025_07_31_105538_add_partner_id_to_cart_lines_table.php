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
            $table->foreignId('partner_id')->nullable()->constrained('users');
            // Or if partners are in a different table:
            // $table->foreignId('partner_id')->nullable()->constrained('partners');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_lines', function (Blueprint $table) {
            //
        });
    }
};
