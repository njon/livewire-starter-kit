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
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->string('type'); // 'new_order', 'new_review', 'new_question'
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data (order_id, product_id, etc.)
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['owner_id', 'is_read']);
            $table->index(['owner_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};