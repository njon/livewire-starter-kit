<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
       Schema::create('product_filters', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('lunar_products');
            $table->foreignId('filter_option_id')->constrained();
            $table->primary(['product_id', 'filter_option_id']);
        });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_filters');
    }
};
