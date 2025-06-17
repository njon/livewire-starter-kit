<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = $this->getWishlistItems();

        $products = Product::whereIn('id', array_keys($wishlist))->get();

        return view('wishlist.index', compact('products'));
    }

    public function store(Request $request)
    {
        $productId = $request->product_id;
        $remove = $request->destroy;

        if (Auth::check()) {
            if ($remove == 'true') {
                // DELETE wishlist item (one-to-many)
                Auth::user()->wishlistItems()
                    ->where('product_id', $productId)
                    ->delete();
            } else {
                // CREATE wishlist item (one-to-many)
                Auth::user()->wishlistItems()
                    ->firstOrCreate(['product_id' => $productId]);
            }
        } else {
            // Guest wishlist (session)
            $wishlist = session()->get('wishlist', []);
            if ($remove == 'true') {
                unset($wishlist[$productId]);
            } else {
                $wishlist[$productId] = true;
            }
            session()->put('wishlist', $wishlist);
        }

        return response()->json(['success' => true]);
    }

    public function ajaxItems()
    {
        return response()->json($this->getWishlistItems());
    }

public function getWishlistItems()
{
    if (Auth::check()) {
        $items = Auth::user()->wishlistItems->pluck('product_id')->toArray();
    } else {
        $items = array_keys(session()->get('wishlist', []));
    }
    
    return array_fill_keys($items, true);
}

    public function transferGuestWishlist($user)
    {
        if (session()->has('wishlist')) {
            $guestWishlist = array_keys(session()->get('wishlist'));
            if (!empty($guestWishlist)) {
                $user->wishlist()->syncWithoutDetaching($guestWishlist);
                session()->forget('wishlist');
                return true;
            }
        }
        return false;
    }
}