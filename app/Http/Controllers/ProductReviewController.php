<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;
use Lunar\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuestReviewInvitation;

class ProductReviewController extends Controller
{
    public function index(Product $product)
    {
        $reviews = $product->reviews()
            ->verified()
            ->latest()
            ->paginate(5);

        return view('products.reviews.index', compact('product', 'reviews'));
    }

    public function create(Request $request, Product $product)
    {
        // Check if user purchased this product
        $purchased = auth()->check() 
            ? $this->checkPurchase(auth()->user(), $product)
            : false;

        return view('products.reviews.create', compact('product', 'purchased'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required_if:user_id,null|string|max:255',
            'email' => 'required_if:user_id,null|email',
            'review' => 'required|string|min:10|max:2000',
            'rating' => 'required|integer|between:1,5',
        ]);

        if (auth()->check()) {
            if (!$this->checkPurchase(auth()->user(), $product)) {
                return back()->with('error', 'You must purchase this product before reviewing');
            }

            $review = $product->reviews()->create([
                'user_id' => auth()->id(),
                'review' => $request->review,
                'rating' => $request->rating,
                'verified_purchase' => true,
            ]);
        } else {
            $token = Str::random(60);

            $review = $product->reviews()->create([
                'guest_email' => $request->email,
                'name' => $request->name,
                'review' => $request->review,
                'rating' => $request->rating,
                'token' => $token,
                'token_expires_at' => now()->addDays(7),
            ]);

            // Mail::to($request->email)->send(new GuestReviewInvitation($review));
        }
        
        return response()->json([
            'message' => auth()->check() 
            ? 'Thank you for your review!' 
            : 'Please check your email to verify your review',
            'product' => $product,
        ]);
    }

    public function verify($token)
    {
        $review = ProductReview::validToken($token)->firstOrFail();

        $review->update([
            'verified_purchase' => true,
            'token' => null,
            'token_expires_at' => null,
        ]);

        return redirect()->route('products.show', $review->product)
            ->with('success', 'Your review has been published!');
    }

    public function helpful(Request $request, ProductReview $review)
    {
        $review->increment('helpful_count');

        return response()->json([
            'count' => $review->helpful_count,
        ]);
    }

    protected function checkPurchase($user, $product)
    {
        // Implement your purchase verification logic
        // This depends on how you track orders in Lunar
        return true; // Temporary
    }
}