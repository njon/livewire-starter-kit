<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('lunar_orders')->onDelete('cascade');
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->string('invoice_path');
            $table->decimal('amount', 10, 2);
            $table->string('currency_code', 3);
            $table->timestamp('generated_at');
            $table->enum('status', ['generated', 'downloaded'])->default('generated');
            $table->timestamps();

            $table->index(['order_id', 'owner_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};