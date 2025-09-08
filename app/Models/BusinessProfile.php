<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OwnerScope;

class BusinessProfile extends Model
{
    use HasFactory, OwnerScope;

    protected $fillable = [
        'owner_id',
        'legal_business_name',
        'dba_trading_name',
        'business_registration_number',
        'tax_identification_number',
        'business_structure',
        'business_address',
        'primary_contact_name',
        'contact_title',
        'business_phone_number',
        'business_email_address',
        'beneficiary_name',
        'bank_name',
        'iban',
        'country',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id', 'owner_id');
    }
}