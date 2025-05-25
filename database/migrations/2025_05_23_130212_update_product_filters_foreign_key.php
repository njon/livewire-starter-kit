<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('product_filters', function (Blueprint $table) {
        // First drop the existing foreign key
        $table->dropForeign(['filter_option_id']);
        
        // Re-add with cascade on delete
        $table->foreign('filter_option_id')
              ->references('id')
              ->on('filter_options')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('product_filters', function (Blueprint $table) {
        $table->dropForeign(['filter_option_id']);
        
        // Re-add original constraint without cascade
        $table->foreign('filter_option_id')
              ->references('id')
              ->on('filter_options');
    });
}
};