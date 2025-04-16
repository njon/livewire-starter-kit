<?php

use App\Livewire\CheckoutPage;
use App\Livewire\CheckoutSuccessPage;
use App\Livewire\CollectionPage;
use App\Livewire\Home;
use App\Livewire\ProductPage;
use App\Livewire\SearchPage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

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

// Route::get('/', Home::class);

// Route::get('/collections/{slug}', CollectionPage::class)->name('collection.view');

// Route::get('/products/{slug}', ProductPage::class)->name('product.view');

// Route::get('search', SearchPage::class)->name('search.view');

// Route::get('checkout', CheckoutPage::class)->name('checkout.view');

// Route::get('checkout/success', CheckoutSuccessPage::class)->name('checkout-success.view');


Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cartItems', [CartController::class, 'getCart'])->name('cart.items');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/checkout', [ProductController::class, 'checkout'])->name('checkout');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show'); // Added this line


Route::resource('products.questions', ProductQuestionController::class)
    ->only(['index', 'store', 'destroy'])
    ->parameters(['questions' => 'productQuestion']);

Route::post('/products/{product}/questions/{productQuestion}/answer', 
    [ProductQuestionController::class, 'answer'])
    ->name('products.questions.answer');


Route::get('/ajax-search', [ProductController::class, 'ajaxResults'])->name('products.ajax-search');


// routes/web.php
Route::get('/xx/add-to-cart', [ProductController::class, 'addToCart'])
    ->name('products.add-to-cart');





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
