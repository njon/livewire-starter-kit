<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Channel::class => \App\Policies\OwnershipPolicy::class,
        \App\Models\Product::class => \App\Policies\OwnershipPolicy::class,
        \Lunar\Models\OrderLine::class => \App\Policies\OwnershipPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

    }
}
