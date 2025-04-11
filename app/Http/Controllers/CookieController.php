<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller
{
    /**
     * Set a cookie
     */
    public function setCookie(Request $request)
    {
        $minutes = 60 * 24 * 30; // 30 days
        $cookieName = 'user_cart';
        $cookieValue = json_encode([
            'product_id' => $request->input('product_id'),
        ]);

        // Option 1: Attach to response
        return response()->json([
            'status' => 'success',
            'message' => 'Cookie set successfully'
        ])->cookie(
            $cookieName,
            $cookieValue,
            $minutes,
            '/',
            null,
            false,
            true // HttpOnly
        );

        // Option 2: Using Cookie facade (alternative)
        // $cookie = Cookie::make($cookieName, $cookieValue, $minutes);
        // return response('Cookie set successfully')->withCookie($cookie);
    }

    /**
     * Get a cookie value
     */
    public function getCookie(Request $request)
    {
        $value = $request->cookie('user_cart');
        
        if ($value) {
            $decoded = json_decode($value, true);
            return response()->json([
                'status' => 'success',
                'data' => $decoded
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Cookie not found'
        ], 404);
    }

    /**
     * Delete a cookie
     */
    public function deleteCookie()
    {
        $cookie = Cookie::forget('user_preferences');
        
        return response('Cookie deleted successfully')
            ->withCookie($cookie);
    }

    /**
     * Example of cookie usage in views
     */
    public function showPage(Request $request)
    {
        $preferences = $request->cookie('user_preferences');
        $theme = 'light'; // default
        
        if ($preferences) {
            $data = json_decode($preferences, true);
            $theme = $data['theme'] ?? $theme;
        }

        return view('welcome', [
            'theme' => $theme
        ]);
    }
}