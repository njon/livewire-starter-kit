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
        Schema::table('filter_options', function (Blueprint $table) {
            // Add your new column(s) here
            $table->string('icon')->nullable()->after('value'); // Example: adding an icon column
            // $table->boolean('is_featured')->default(false)->after('sort_order'); // Another example
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('filter_options', function (Blueprint $table) {
            // Reverse the changes (for rollback)
            $table->dropColumn('icon');
            // $table->dropColumn('is_featured');
        });
    }
};