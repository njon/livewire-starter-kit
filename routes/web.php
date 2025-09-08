<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductQuestionController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\MediaUploadController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminStoreController;
use App\Http\Controllers\Admin\AdminOrdersController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDiscountController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\AdminProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

Route::post('/refresh-lunar-cache', [CartController::class, 'refreshLunarCache'])->name('lunar.cache.refresh');
Route::post('/remove-orders', [CartController::class, 'removeOrders'])->name('lunar.orders.remove');

Route::group(['prefix' => 'admin/products/{product}/variants', 'as' => 'admin.products.variants.'], function() {
    Route::post('/', [ProductVariantController::class, 'store'])->name('store');
    Route::put('/{variant}', [ProductVariantController::class, 'update'])->name('update');
    Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('destroy');
});

Route::get('/', [HomepageController::class, 'index']);

Route::prefix('admin')->middleware(['auth', 'owner'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('products', AdminServiceController::class)->names('admin.products');
    Route::put('/products/{product}/media', [AdminServiceController::class, 'storeMedia'])->name('admin.products.media.store');
    Route::delete('/products/{product}/media', [AdminServiceController::class, 'destroyMedia'])->name('admin.products.media.destroy');
    
    Route::resource('stores', AdminStoreController::class)->except(['show']);
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::resource('discounts', AdminDiscountController::class)->except(['show'])->names('admin.discounts');


    Route::get('/orders', [AdminOrdersController::class, 'index'])->name('admin.orders.index');
    Route::get('/order/{order}', [AdminOrdersController::class, 'show'])->name('admin.orders.show');
    Route::post('/order/{id}/status', [AdminOrdersController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::post('/order/{id}/refund', [AdminOrdersController::class, 'refund'])->name('admin.orders.refund');
    Route::get('/order/{id}/download', [AdminOrdersController::class, 'downloadPdf'])->name('admin.orders.download');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/recent', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'recent'])->name('admin.notifications.recent');
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-read');

    // Business Profile
    Route::get('/profile/business', [AdminProfileController::class, 'business'])->name('admin.profile.business');
    Route::post('/profile/business', [AdminProfileController::class, 'updateBusiness'])->name('admin.profile.business.update');

});

Route::prefix('checkout')->name('checkout.')->controller(CheckoutController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/success/{reference_id}', 'order')->name('success');
    Route::post('/', 'checkout')->name('store');
    Route::post('/create-payment-intent', 'processStripePayment')->name('payment-intent');
    Route::post('/complete-order', 'completeOrder')->name('complete');
});

// Authentication Routes
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

// Paypal
Route::group(['middleware' => ['web']], function () {
    Route::post('/paypal/create', [PayPalController::class, 'create'])->name('paypal.create');
    Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
});
Route::post('/paypal/webhook', [PayPalController::class, 'webhook'])->name('paypal.webhook');

// Auth
Route::controller(AuthController::class)->group(function() {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::post('/login-partner', 'loginPartner')->name('login-partner');
    Route::post('/login-partner2', 'loginPartner2')->name('login-partner2');
    Route::post('/login-visitor', 'loginVisitor')->name('login-visitor');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');

    
    
    Route::get('/auth/{provider}', 'socialRedirect')->where('provider', 'facebook|google');
    Route::get('/auth/{provider}/callback', 'socialCallback')->where('provider', 'facebook|google');
});

// Cart
Route::resource('cart', CartController::class)->only(['index', 'update', 'destroy'])->parameters(['cart' => 'ProductVariant']);
Route::get('/canvasItems', [CartController::class, 'canvasItems'])->name('cart.canvas-items');
Route::get('/ajax-search', [ProductController::class, 'ajaxResults'])->name('products.ajax-search');

// Product Questions 
Route::resource('products.questions', ProductQuestionController::class)->only(['index', 'store', 'destroy'])->parameters(['questions' => 'productQuestion']);
Route::post('/product/{product}/answer/{productquestion}', [ProductQuestionController::class, 'answer'])->name('products.questions.answer');

// Reviews
Route::resource('products.reviews', ProductReviewController::class)->only(['index', 'create', 'store']);
Route::get('/reviews/verify/{token}', [ProductReviewController::class, 'verify'])->name('reviews.verify');
Route::post('/reviews/{review}/helpful', [ProductReviewController::class, 'helpful'])->name('reviews.helpful');

// Wishlist Routes
Route::resource('wishlist', WishlistController::class)->only(['index', 'store', 'destroy']);
Route::get('wishlist/ajax-items', [WishlistController::class, 'ajaxItems'])->name('wishlist.ajaxItems');

// Catch-all Route for Products and Collections
Route::get('{slug}', function($slug) {
    
    $d = request()->query('d');

    if ($product = \App\Models\Product::findBySlug($slug)) {
        if($d) {
            dd($product);
        }
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