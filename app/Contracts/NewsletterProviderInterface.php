<?php

namespace App\Contracts;

interface NewsletterProviderInterface
{
    public function subscribe(string $email, array $data = []): bool;

    public function unsubscribe(string $email): bool;

    public function isSubscribed(string $email): bool;

    public function getSubscriberCount(): int;
}