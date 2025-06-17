<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductQuestionController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/xxx', [CartController::class, 'xxx']);

Route::post('/create-payment-intent', [CartController::class, 'processStripePayment']);

Route::post('/complete-order', [CartController::class, 'completeOrder']);






// User Profile Routes
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    
    // Password
    Route::put('/password', [UserController::class, 'updatePassword'])->name('password.update');
    
    // Orders
    Route::get('/profile/orders', [UserController::class, 'showOrders'])->name('profile.orders');
    
    // Wishlist
    Route::get('/profile/wishlist', [UserController::class, 'showWishlist'])->name('profile.wishlist');
    
    // Newsletter
    Route::post('/profile/newsletter', [UserController::class, 'updateNewsletter'])->name('newsletter.update');
    
    // Bookings
    Route::get('/profile/bookings', [UserController::class, 'showBookings'])->name('profile.bookings');
    
    // Invitations
    Route::post('/profile/invite', [UserController::class, 'sendInvitations'])->name('invite.send');
    
    // Account Deletion
    Route::get('/profile/delete', [UserController::class, 'showDeleteAccount'])->name('profile.delete');
    Route::delete('/profile/delete', [UserController::class, 'deleteAccount'])->name('profile.destroy');
});







// Authentication Routes
Route::controller(AuthController::class)->group(function() {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
    
    // Social Login Routes
    Route::get('/auth/{provider}', 'socialRedirect')->where('provider', 'facebook|google');
    Route::get('/auth/{provider}/callback', 'socialCallback')->where('provider', 'facebook|google');
});



// Catch-all Route for Products and Collections
Route::get('{slug}', function($slug) {
    if ($product = \App\Models\Product::findBySlug($slug)) {
        return app(ProductController::class)->show($product);
    }

    if ($collection = App\Models\Collection::findBySlug($slug)) {
        return app(ProductController::class)->category($collection);
    }

    if (in_array($slug, ['privacy-policy', 'refund-policy', 'terms-of-service'])) {
        return app(ProductController::class)->page($slug);
    }

    return view('errors.404', ['message' => 'Page not found']);

})->where('slug', '.*');

