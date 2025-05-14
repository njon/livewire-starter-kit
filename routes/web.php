<?php

use App\Livewire\CheckoutPage;
use App\Livewire\CheckoutSuccessPage;
use App\Livewire\CollectionPage;
use App\Livewire\Home;
use App\Livewire\ProductPage;
use App\Livewire\SearchPage;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Models\Product;
use App\Http\Controllers\ProductQuestionController;
use App\Models\ProductQuestion;
use App\Http\Controllers\ProductReviewController;
use \Lunar\Models\Price;
use Lunar\Facades\Pricing;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;
use Lunar\Models\TaxRate;
use Lunar\Models\TaxRateType;
use Lunar\Models\TaxRateAmountType;
use Lunar\Models\TaxRateAmount;
use Lunar\Models\Discount;
use Lunar\Models\DiscountPurchasable;
use Lunar\Models\DiscountPurchasableType;
use Lunar\Models\DiscountType;
use Illuminate\Support\Facades\URL;



// Get the first variant (purchasable item)

// dd($product);


// dd([
//     'original_price' => $originalPrice,
//     'discounted_price' => $discountedPrice,
//     'has_discount' => $originalPrice != $discountedPrice,
//     'currency' => $priceResponse->matched->price->currency->code
// ]);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// @ todo Resource routes for cart. Inject CartService in the controller
// Route::post('/cart/add/{cart}', [CartController::class, 'store']);
// Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
// Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
// Route::get('/cart', [CartController::class, 'index'])->name('cart');
// Route::get('/canvasItems', [CartController::class, 'canvasItems'])->name('canvas.items');

Route::resource('cart', CartController::class)
    ->only(['index', 'update', 'destroy'])
    ->parameters(['cart' => 'ProductVariant']);
    
    
Route::get('/canvasItems', [CartController::class, 'canvasItems'])->name('cart.canvas-items');



Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/checkout', [ProductController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');


Route::resource('products.questions', ProductQuestionController::class)
    ->only(['index', 'store', 'destroy'])
    ->parameters(['questions' => 'productQuestion']);

Route::post('/product/{product}/answer/{productquestion}', [ProductQuestionController::class, 'answer'])
    ->name('products.questions.answer');

Route::get('/ajax-search', [ProductController::class, 'ajaxResults'])->name('products.ajax-search');

Route::prefix('products/{product}')->group(function() {
    Route::get('/reviews', [ProductReviewController::class, 'index'])->name('products.reviews.index');
    Route::get('/reviews/create', [ProductReviewController::class, 'create'])->name('products.reviews.create');
    Route::post('/reviews', [ProductReviewController::class, 'store'])->name('products.reviews.store');
});

Route::get('/reviews/verify/{token}', [ProductReviewController::class, 'verify'])->name('reviews.verify');
Route::post('/reviews/{review}/helpful', [ProductReviewController::class, 'helpful'])->name('reviews.helpful');




Route::get('/test-email', function() {
    $review = App\Models\ProductReview::first();
    return new App\Mail\GuestReviewInvitation($review);
});
















Route::get('{slug}', function($slug) {
    if ($product = \App\Models\Product::with(['defaultUrl'])
        ->whereHas('defaultUrl', fn($q) => $q->where('slug', $slug))
        ->first()) {
        return app(ProductController::class)->show($slug);
    }

    if ($collection = \Lunar\Models\Collection::with(['defaultUrl'])
        ->whereHas('defaultUrl', fn($q) => $q->where('slug', $slug))
        ->first()) {
        return app(ProductController::class)->byCollection($slug);
    }
})->where('slug', '.*');