<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            
            // Core Business Information
            $table->string('legal_business_name')->nullable();
            $table->string('dba_trading_name')->nullable();
            $table->string('business_registration_number', 100)->nullable();
            $table->string('tax_identification_number', 100)->nullable();
            $table->string('business_structure')->nullable();
            $table->text('business_address')->nullable();
            
            // Primary Contact Information
            $table->string('primary_contact_name')->nullable();
            $table->string('contact_title')->nullable();
            $table->string('business_phone_number', 50)->nullable();
            $table->string('business_email_address')->nullable();
            
            // Banking & Payment Information
            $table->string('beneficiary_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('iban', 100)->nullable();
            
            // Location Information
            $table->string('country', 2)->default('GR');
            
            $table->timestamps();
            
            $table->unique('owner_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_profiles');
    }
};