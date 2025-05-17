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
        $productId = $request['product_id'];
        
        if (Auth::check()) {
            Auth::user()->wishlist()->syncWithoutDetaching([$productId]);
        } else {
            $wishlist = session()->get('wishlist', []);
            $wishlist[$productId] = true;
            session()->put('wishlist', $wishlist);
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request)
    {
        $productId = $request['product_id'];

        if (Auth::check()) {
            Auth::user()->wishlist()->detach($productId);
        } else {
            $wishlist = session()->get('wishlist', []);
            unset($wishlist[$productId]);
            session()->put('wishlist', $wishlist);
        }

        return response()->json(['success' => true]);
    }

    public function ajaxItems()
    {
        return response()->json($this->getWishlistItems());
    }

    private function getWishlistItems()
    {
        if (Auth::check()) {
            $items = Auth::user()->wishlist()->pluck('products.id')->toArray();
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