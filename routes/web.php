<?php

use App\Livewire\CheckoutPage;
use App\Livewire\CheckoutSuccessPage;
use App\Livewire\CollectionPage;
use App\Livewire\Home;
use App\Livewire\ProductPage;
use App\Livewire\SearchPage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CookieController;

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


Route::get('/get-cookie', [CookieController::class, 'getCookie']);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show'); // Added this line
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

Route::get('{slug}', function($slug) {
    // Try product first
    if ($product = \App\Models\Product::with(['defaultUrl'])
        ->whereHas('defaultUrl', fn($q) => $q->where('slug', $slug))
        ->first()) {
        return app(ProductController::class)->show($slug);
    }

    // Then try collection
    if ($collection = \Lunar\Models\Collection::with(['defaultUrl'])
        ->whereHas('defaultUrl', fn($q) => $q->where('slug', $slug))
        ->first()) {
        return app(ProductController::class)->byCollection($slug);
    }
})->where('slug', '.*');






Route::post('/set-cookie', [CookieController::class, 'setCookie']);
Route::get('/delete-cookie', [CookieController::class, 'deleteCookie']);
Route::get('/page-with-cookie', [CookieController::class, 'showPage']);