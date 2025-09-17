<?php

namespace App\Services\Newsletter;

use App\Contracts\NewsletterProviderInterface;
use App\Models\Newsletter;

class DatabaseNewsletterProvider implements NewsletterProviderInterface
{
    public function subscribe(string $email, array $data = []): bool
    {
        try {
            Newsletter::updateOrCreate(
                ['email' => $email],
                [
                    'email' => $email,
                    'name' => $data['name'] ?? null,
                    'subscribed_at' => now(),
                    'is_active' => true,
                ]
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function unsubscribe(string $email): bool
    {
        try {
            Newsletter::where('email', $email)->update([
                'is_active' => false,
                'unsubscribed_at' => now(),
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function isSubscribed(string $email): bool
    {
        return Newsletter::where('email', $email)
            ->where('is_active', true)
            ->exists();
    }

    public function getSubscriberCount(): int
    {
        return Newsletter::where('is_active', true)->count();
    }
}