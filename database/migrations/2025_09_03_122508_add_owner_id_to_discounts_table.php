<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'discounts', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable()->after('id');
        });
    }


    public function down(): void
    {
        Schema::table($this->prefix.'discounts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('owner_id');
        });
    }
};