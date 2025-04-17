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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); // Changed from foreignId()
            $table->unsignedBigInteger('user_id')->nullable(); // Changed from foreignId()
            $table->string('guest_email')->nullable();
            $table->string('name')->nullable();
            $table->text('review');
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->unsignedInteger('helpful_count')->default(0);
            $table->boolean('verified_purchase')->default(false);
            $table->string('token')->nullable()->unique(); // For guest reviews
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamps();
        
            // Add indexes for better performance
            $table->index('product_id');
            $table->index('user_id');
            $table->index('token');
        });

        // Schema::create('product_reviews', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('product_id')->constrained('lunar_products');
        //     $table->foreignId('user_id')->nullable()->constrained('users');
        //     $table->string('guest_email')->nullable();
        //     $table->string('name')->nullable();
        //     $table->text('review');
        //     $table->unsignedTinyInteger('rating'); // 1-5
        //     $table->unsignedInteger('helpful_count')->default(0);
        //     $table->boolean('verified_purchase')->default(false);
        //     $table->string('token')->nullable()->unique(); // For guest reviews
        //     $table->timestamp('token_expires_at')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
