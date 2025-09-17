<?php

namespace App\Services;

use App\Contracts\NewsletterProviderInterface;

class NewsletterService
{
    protected NewsletterProviderInterface $provider;

    public function __construct(NewsletterProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function subscribe(string $email, array $data = []): array
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Invalid email address.'
            ];
        }

        if ($this->provider->isSubscribed($email)) {
            return [
                'success' => false,
                'message' => 'This email is already subscribed to our newsletter.'
            ];
        }

        $result = $this->provider->subscribe($email, $data);

        return [
            'success' => $result,
            'message' => $result
                ? 'Successfully subscribed to our newsletter!'
                : 'Failed to subscribe. Please try again.'
        ];
    }

    public function unsubscribe(string $email): array
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Invalid email address.'
            ];
        }

        $result = $this->provider->unsubscribe($email);

        return [
            'success' => $result,
            'message' => $result
                ? 'Successfully unsubscribed from our newsletter.'
                : 'Failed to unsubscribe. Please try again.'
        ];
    }

    public function isSubscribed(string $email): bool
    {
        return $this->provider->isSubscribed($email);
    }

    public function getSubscriberCount(): int
    {
        return $this->provider->getSubscriberCount();
    }
}