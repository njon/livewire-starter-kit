<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleSitePreferences
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Set cookie if not exists
        if (!$request->hasCookie('user_preferences')) {
            $value = json_encode([
                'theme' => 'light',
                'language' => app()->getLocale(),
                'first_visit' => now()->toDateTimeString()
            ]);
            
            return $response->cookie(
                'user_preferences',
                $value,
                60 * 24 * 30, // 30 days
                '/',
                null,
                false,
                true
            );
        }
        
        return $response;
    }
}