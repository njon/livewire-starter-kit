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
        Schema::table('lunar_cart_line_discount', function (Blueprint $table) {
            // First, drop the existing incorrect foreign key constraint
            $table->dropForeign(['cart_line_id']);
            
            // Add the correct foreign key constraint pointing to cart_lines table
            $table->foreign('cart_line_id')
                  ->references('id')
                  ->on('lunar_cart_lines')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lunar_cart_line_discount', function (Blueprint $table) {
            // Drop the correct foreign key
            $table->dropForeign(['cart_line_id']);
            
            // Re-add the original (incorrect) foreign key constraint
            $table->foreign('cart_line_id')
                  ->references('id')
                  ->on('lunar_carts')
                  ->onDelete('cascade');
        });
    }
};