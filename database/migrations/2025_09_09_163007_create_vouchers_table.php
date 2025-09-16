// database/migrations/2024_01_01_000000_create_vouchers_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('order_id')->onDelete('cascade');
            $table->foreignId('order_line_id')->constrained('order_lines')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->onDelete('set null');
            $table->timestamp('expires_at');
            $table->timestamp('created_at')->useCurrent();
            $table->enum('status', ['active', 'used', 'expired', 'cancelled'])->default('active');
            
            $table->index(['code', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['expires_at', 'status']);
            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};