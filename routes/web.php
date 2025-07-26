<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductQuestionController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\MediaUploadController;
use App\Http\Controllers\Admin\AdminProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

Route::get('/', [HomepageController::class, 'index']);

Route::post('/upload-media', [MediaUploadController::class, 'upload'])->name('media.upload');
Route::delete('/delete-media/{media}', [MediaUploadController::class, 'delete'])->name('media.delete');

// Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
Route::prefix('admin')->group(function () {
    Route::get('/check-slug', [AdminProductController::class, 'slugExists']);
    Route::resource('products', AdminProductController::class)
        ->names([
            'index' => 'admin.products.index',
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'show' => 'admin.products.show',
            'edit' => 'admin.products.edit',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy',
        ]);
});


// Cart Routes
Route::resource('cart', CartController::class)->only(['index', 'update', 'destroy'])->parameters(['cart' => 'ProductVariant']);
    
Route::get('/canvasItems', [CartController::class, 'canvasItems'])->name('cart.canvas-items');

// Checkout Routes
Route::prefix('checkout')->name('checkout.')->controller(CheckoutController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/success/{reference_id}', 'order')->name('success');
    Route::post('/', 'checkout')->name('store');
    Route::post('/create-payment-intent', 'processStripePayment')->name('payment-intent');
    Route::post('/complete-order', 'completeOrder')->name('complete');
});

// Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/ajax-search', [ProductController::class, 'ajaxResults'])->name('products.ajax-search');

// Product Questions Routes
Route::resource('products.questions', ProductQuestionController::class)->only(['index', 'store', 'destroy'])->parameters(['questions' => 'productQuestion']);

Route::post('/product/{product}/answer/{productquestion}', [ProductQuestionController::class, 'answer'])
    ->name('products.questions.answer');

// Product Reviews Routes
Route::resource('products.reviews', ProductReviewController::class)->only(['index', 'create', 'store']);

Route::get('/reviews/verify/{token}', [ProductReviewController::class, 'verify'])->name('reviews.verify');
Route::post('/reviews/{review}/helpful', [ProductReviewController::class, 'helpful'])->name('reviews.helpful');

// Wishlist Routes
Route::resource('wishlist', WishlistController::class)->only(['index', 'store', 'destroy']);
Route::get('wishlist/ajax-items', [WishlistController::class, 'ajaxItems'])
    ->name('wishlist.ajaxItems');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [UserController::class, 'updatePassword'])->name('password.update');
    Route::get('/profile/orders', [UserController::class, 'showOrders'])->name('profile.orders');
    Route::get('/profile/wishlist', [UserController::class, 'showWishlist'])->name('profile.wishlist');
    Route::post('/profile/newsletter', [UserController::class, 'updateNewsletter'])->name('newsletter.update');
    Route::get('/profile/bookings', [UserController::class, 'showBookings'])->name('profile.bookings');
    Route::post('/profile/invite', [UserController::class, 'sendInvitations'])->name('invite.send');
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


Route::post('/paypal/webhook', [PayPalController::class, 'webhook'])->name('paypal.webhook');


Route::group(['middleware' => ['web']], function () {
    Route::post('/paypal/create', [PayPalController::class, 'create'])->name('paypal.create');
    Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
});

// routes/admin.php










// Catch-all Route for Products and Collections
Route::get('{slug}', function($slug) {
    if ($product = \App\Models\Product::findBySlug($slug)) {
        return app(ProductController::class)->show($product);
    }

    if ($collection = Lunar\Models\Collection::whereHas('defaultUrl', fn($q) => $q->where('slug', $slug))->first()) {
        return app(ProductController::class)->category($collection);
    }

     if (in_array($slug, ['privacy-policy', 'refund-policy', 'terms-of-service', 'frequently-asked-questions', 'partners'])) {
        return app(ProductController::class)->page($slug);
    }

    return view('errors.404', ['message' => 'Page not found']);

})->where('slug', '.*');