<?php

namespace App\Providers;

use App\Contracts\NewsletterProviderInterface;
use App\Services\Newsletter\DatabaseNewsletterProvider;
use App\Services\Newsletter\MailchimpNewsletterProvider;
use App\Services\NewsletterService;
use Illuminate\Support\ServiceProvider;

class NewsletterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NewsletterProviderInterface::class, function ($app) {
            $provider = config('newsletter.provider', 'database');

            return match ($provider) {
                'mailchimp' => new MailchimpNewsletterProvider(),
                'database' => new DatabaseNewsletterProvider(),
                default => new DatabaseNewsletterProvider(),
            };
        });

        $this->app->singleton(NewsletterService::class, function ($app) {
            return new NewsletterService($app->make(NewsletterProviderInterface::class));
        });
    }

    public function boot(): void
    {
        //
    }
}