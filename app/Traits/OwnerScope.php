<?php

namespace App\Traits;

trait OwnerScope
{
    public static function bootOwnerScope()
    {
        static::addGlobalScope('owner', function ($builder) {
            // Only apply scope if:
            // 1. We're in an HTTP request (not console/queue)
            // 2. The request is under /admin/*
            // 3. User is authenticated

            if (app()->runningInConsole()) {
                return;
            }

            $request = app('request');
            $auth = app('auth');
            
            if ($request->is('admin*') && $auth->check()) {
                $builder->where('owner_id', $auth->user()->owner_id);
            }
        });
    }
        public function scopeForOwner($query)
    {
        return $query->where('owner_id', auth()->id());
    }
}

