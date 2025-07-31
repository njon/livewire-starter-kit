<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lunar_channels', function (Blueprint $table) {
            $table->text('description')->nullable()->after('url');
            $table->json('working_hours')->nullable()->after('description');
            $table->string('address')->nullable()->after('working_hours');
            $table->string('map_location')->nullable()->after('address');
            $table->string('phone')->nullable()->after('map_location');
            $table->string('email')->nullable()->after('phone');
            $table->string('website')->nullable()->after('email');
        });
    }

    public function down()
    {
        Schema::table('lunar_channels', function (Blueprint $table) {
            $table->dropColumn([
            'description',
            'working_hours',
            'address',
            'map_location',
            'phone',
            'email',
            'website',
            ]);
        });
    }
};