<?php

namespace App\Providers;

use App\Modifiers\ShippingModifier;
use Illuminate\Support\ServiceProvider;
use Lunar\Admin\Support\Facades\LunarPanel;
use Lunar\Base\ShippingModifiers;
use Lunar\Shipping\ShippingPlugin;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        LunarPanel::panel(
            fn ($panel) => $panel->plugins([
                new ShippingPlugin,
            ])
        )
            ->register();


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(ShippingModifiers $shippingModifiers): void
    {
        Builder::macro('ownedByUser', function ($name = '', $column = 'owner_id') {
            $role = auth()->user()->role == 'super_admin' ?? false;
            $userId = $userId ?? auth()->id();
            $column = $name ? $name . '.' . $column : $column;

            if (!$userId) {
                // Return empty result if no user is authenticated
                return $this->whereNull($column);
            }

            if($role) {
                return $this->where($column, '!=', 0);
            }

            return $this->where($column, $userId);
        });

        Collection::macro('ownedByUser', function ($userId = null, $column = 'owner_id') {
            $userId = $userId ?? auth()->id();
            
            return $this->filter(function ($item) use ($userId, $column) {
                return $item->{$column} == $userId;
            });
        });

        $shippingModifiers->add(
            ShippingModifier::class
        );

        \Lunar\Facades\ModelManifest::replace(
            \Lunar\Models\Contracts\Product::class,
            \App\Models\Product::class,

            \Lunar\Models\Order::class,
            \App\Models\Order::class,

            \Lunar\Models\Contracts\Channel::class,
            \App\Models\Channel::class,
        );
        

        Paginator::useBootstrapFive(); // or Paginator::useBootstrapFive();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            URL::forceRootUrl(config('app.url'));
        }
    }
}
