@extends('layouts.email')

@section('content')
    <div class="email-container">
        <h1>Verify Your Product Review</h1>
        
        <p>Hello!</p>
        
        <p>Thank you for reviewing our product. Please click the button below to verify your review:</p>
        
        <div class="button-container">
            <a href="{{ $review->verificationUrl }}" class="button">
                Verify My Review
            </a>
        </div>
        
        <p>If you didn't request this review, please ignore this email.</p>
        
        <p class="expiry-notice">
            This verification link will expire in 7 days.
        </p>
    </div>
@endsection

@section('footer')
    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
@endsection