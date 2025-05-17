<?php




namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Wishlist extends Model
{
    protected $fillable = ['user_id', 'product_id'];

    // Relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ==================== BUSINESS LOGIC ====================
    // Get all wishlist items (for guest or authenticated user)
    public static function getItems()
    {
        if (Auth::check()) {
            return self::getUserWishlist();
        }
        return self::getGuestWishlist();
    }

    // Get wishlist for authenticated user (from DB)
    protected static function getUserWishlist()
    {
        return Auth::user()->wishlistItems()->with('product')->get()->pluck('id');
    }

    // Get wishlist for guest (from session)
    protected static function getGuestWishlist()
    {
        $sessionItems = session('wishlist', []);
        return $sessionItems;
    }

    // Add item to wishlist (handles both guest and user)
    public static function addItem(Product $product)
    {
        if (Auth::check()) {
            return Auth::user()->wishlistItems()->firstOrCreate(['product_id' => $product->id]);
        }

        $wishlist = session('wishlist', []);
        $wishlist[$product->id] = true;
        session(['wishlist' => $wishlist]);
    }

    // Remove item from wishlist (handles both guest and user)
    public static function removeItem(Product $product)
    {
        if (Auth::check()) {
            return Auth::user()->wishlistItems()->where('product_id', $product->id)->delete();
        }

        $wishlist = session('wishlist', []);
        unset($wishlist[$product->id]);
        session(['wishlist' => $wishlist]);
    }
}