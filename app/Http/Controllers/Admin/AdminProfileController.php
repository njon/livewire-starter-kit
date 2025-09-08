<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    public function business()
    {
        $profile = BusinessProfile::where('owner_id', Auth::user()->owner_id)->first();
        
        return view('admin.profile.business', compact('profile'));
    }

    public function updateBusiness(Request $request)
    {
        $validated = $request->validate([
            'legal_business_name' => 'required|string|max:255',
            'dba_trading_name' => 'nullable|string|max:255',
            'business_registration_number' => 'required|string|max:100',
            'tax_identification_number' => 'required|string|max:100',
            'business_structure' => 'required|string|in:AE,EPE,IKE,OE,EE,Sole',
            'business_address' => 'required|string|max:1000',
            'primary_contact_name' => 'required|string|max:255',
            'contact_title' => 'required|string|in:Owner,CEO,Managing Director,Finance Manager,Other',
            'business_phone_number' => 'required|string|max:50',
            'business_email_address' => 'required|email|max:255',
            'beneficiary_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'iban' => 'required|string|max:100|regex:/^[0-9A-Z\s]+$/',
        ]);

        // Clean and validate IBAN format
        $validated['iban'] = strtoupper(str_replace(' ', '', $validated['iban']));
        
        // Clean phone number
        $validated['business_phone_number'] = preg_replace('/\D/', '', $validated['business_phone_number']);

        $validated['owner_id'] = Auth::user()->owner_id;

        BusinessProfile::updateOrCreate(
            ['owner_id' => Auth::user()->owner_id],
            $validated
        );

        return redirect()->back()->with('success', 'Business profile saved successfully! Your information has been securely stored.');
    }
}