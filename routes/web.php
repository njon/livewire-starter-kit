<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductQuestionController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomepageController;

Route::get('/', [HomepageController::class, 'index']);

Route::resource('cart', CartController::class)
    ->only(['index', 'update', 'destroy'])
    ->parameters(['cart' => 'ProductVariant']);
    
Route::get('/checkout', [CartController::class, 'checkoutpage']);
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout.store');
Route::get('/canvasItems', [CartController::class, 'canvasItems'])->name('cart.canvas-items');

// Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/ajax-search', [ProductController::class, 'ajaxResults'])->name('products.ajax-search');
Route::get('/privacy-policy', [ProductController::class, 'page']);

// Product Questions Routes
Route::resource('products.questions', ProductQuestionController::class)
    ->only(['index', 'store', 'destroy'])
    ->parameters(['questions' => 'productQuestion']);
Route::post('/product/{product}/answer/{productquestion}', [ProductQuestionController::class, 'answer'])
    ->name('products.questions.answer');

// Product Reviews Routes
Route::prefix('products/{product}')->group(function() {
    Route::get('/reviews', [ProductReviewController::class, 'index'])->name('products.reviews.index');
    Route::get('/reviews/create', [ProductReviewController::class, 'create'])->name('products.reviews.create');
    Route::post('/reviews', [ProductReviewController::class, 'store'])->name('products.reviews.store');
});
Route::get('/reviews/verify/{token}', [ProductReviewController::class, 'verify'])->name('reviews.verify');
Route::post('/reviews/{review}/helpful', [ProductReviewController::class, 'helpful'])->name('reviews.helpful');

// Wishlist Routes
Route::resource('wishlist', WishlistController::class)->only(['index', 'store', 'destroy']);
Route::get('wishlist/ajax-items', [WishlistController::class, 'ajaxItems'])
    ->name('wishlist.ajaxItems');

Route::get('/checkout/success/{order}', [CartController::class, 'order']);


// Catch-all Route for Products and Collections
Route::get('{slug}', function($slug) {
    if ($product = \App\Models\Product::findBySlug($slug)) {
        return app(ProductController::class)->show($product);
    }

    if ($collection = App\Models\Collection::findBySlug($slug)) {
        return app(ProductController::class)->category($collection);
    }

})->where('slug', '.*');

