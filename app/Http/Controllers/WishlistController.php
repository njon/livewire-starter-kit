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
        $remove = $request['destroy'];
        
        if (Auth::check()) {
            $remove == 'true'
                ? Auth::user()->wishlist()->syncWithoutDetaching([$productId])
                : Auth::user()->wishlist()->detach($productId);
        } else {
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