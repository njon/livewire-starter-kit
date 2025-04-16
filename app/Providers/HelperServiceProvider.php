<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    public function register()
    {
        require_once app_path('Helpers/lunar_helpers.php');
    }
    
    public function boot()
    {
        //
    }
}