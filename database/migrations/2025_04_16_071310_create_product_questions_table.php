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
        Schema::create('product_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); // Changed from foreignId()
            $table->unsignedBigInteger('user_id')->nullable(); // Changed from foreignId()
            $table->text('question');
            $table->text('answer')->nullable();
            $table->unsignedBigInteger('answered_by')->nullable(); // Changed from foreignId()
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();
            

            // Schema::create('product_questions', function (Blueprint $table) {
            //     $table->id();
            //     $table->foreignId('product_id')->constrained('products');
            //     $table->foreignId('user_id')->nullable()->constrained('users');
            //     $table->text('question');
            //     $table->text('answer')->nullable();
            //     $table->foreignId('answered_by')->nullable()->constrained('users');
            //     $table->timestamp('answered_at')->nullable();
            //     $table->timestamps();
            // });
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_questions');
    }
};
