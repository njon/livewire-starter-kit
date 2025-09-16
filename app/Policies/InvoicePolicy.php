<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
{
    public function view(User $user, Invoice $invoice): bool
    {
        // Users can only view their own invoices or invoices from their owner
        return $invoice->owner_id === ($user->owner_id ?? $user->id) ||
               $invoice->order->user_id === $user->id;
    }

    public function download(User $user, Invoice $invoice): bool
    {
        return $this->view($user, $invoice);
    }
}