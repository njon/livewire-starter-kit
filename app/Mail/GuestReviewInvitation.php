<?php

namespace App\Mail;

use App\Models\ProductReview;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GuestReviewInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $review;
    public $verificationUrl;

    public function __construct(ProductReview $review)
    {
        $this->review = $review;
        $this->verificationUrl = route('reviews.verify', ['token' => $review->token]);
    }

    public function build()
    {
        return $this->subject('Please verify your product review')
                   ->markdown('emails.review-invitation');
    }
}