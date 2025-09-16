<?php

namespace App\Services;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use Lunar\Models\Order;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateInvoice(Order $order): string
    {
        $seller = new Party([
            'name'          => config('invoice.seller.name'),
            'phone'         => config('invoice.seller.phone'),
            'custom_fields' => [
                'email' => config('invoice.seller.email'),
                'website' => config('invoice.seller.website'),
                'address' => config('invoice.seller.address'),
            ],
        ]);

        // Get billing address, create default if none exists
        $billingAddress = $order->billingAddress ?? $order->shippingAddress ?? (object)[
            'first_name' => 'Customer',
            'last_name' => '',
            'contact_email' => 'customer@example.com',
            'contact_phone' => '',
            'line_one' => '',
            'city' => '',
            'postcode' => ''
        ];

        $customer = new Buyer([
            'name'          => trim($billingAddress->first_name . ' ' . $billingAddress->last_name) ?: 'Customer',
            'custom_fields' => [
                'email' => $billingAddress->contact_email ?: 'customer@example.com',
                'phone' => $billingAddress->contact_phone ?: '',
                'address' => trim($billingAddress->line_one . ', ' . $billingAddress->city . ' ' . $billingAddress->postcode, ', '),
            ],
        ]);

        $items = [];
        foreach ($order->lines as $line) {
            $productName = $line->purchasable->translateAttribute('name') ?? 'Product #' . $line->purchasable_id;

            $items[] = InvoiceItem::make($productName)
                ->pricePerUnit($line->unit_price->value / 100) // Convert from cents
                ->quantity($line->quantity)
                ->discount(($line->discount_total?->value ?? 0) / 100); // Convert from cents
        }

        $storagePath = config('invoice.storage.path', 'invoices/');
        $invoiceFilename = $order->reference . '-invoice'; // Remove .pdf extension
        $fullPath = rtrim($storagePath, '/') . '/' . $invoiceFilename;

        $invoice = Invoice::make('invoice')
            ->series($this->generateSeries())
            ->sequence($order->id)
            ->serialNumberFormat('{SERIES}-{SEQUENCE}')
            ->seller($seller)
            ->buyer($customer)
            ->date(now())
            ->dateFormat('m/d/Y')
            ->payUntilDays(config('invoice.defaults.pay_until_days', 30))
            ->currencySymbol(config('invoice.defaults.currency_symbol', '€'))
            ->currencyCode($order->currency_code ?? config('invoice.defaults.currency_code', 'EUR'))
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($fullPath) // Set filename to the full path without .pdf
            ->addItems($items)
            ->notes('Thank you for your purchase!');

        // Add logo if exists
        $logoPath = public_path(config('invoice.defaults.logo_path'));
        if (file_exists($logoPath)) {
            $invoice->logo($logoPath);
        }

        // Set custom tax and totals
        $invoice->totalTaxes = ($order->tax_total?->value ?? 0) / 100;
        $invoice->totalAmount = ($order->total?->value ?? 0) / 100;
        $invoice->totalDiscount = ($order->discount_total?->value ?? 0) / 100;

        // The save method will use the filename set above and add .pdf
        $invoice->save(config('invoice.storage.disk', 'public'));

        return $fullPath . '.pdf'; // Return path with extension
    }

    public function generateTestInvoice(?Order $order = null): string
    {
        if (!$order) {
            $order = Order::where('status', 'payment-received')->inRandomOrder()->first();

            if (!$order) {
                throw new \Exception('No completed orders found to generate test invoice');
            }
        }

        return $this->generateInvoice($order);
    }

    private function generateSeries(): string
    {
        return config('invoice.defaults.series_prefix', 'INV') . date('Y');
    }

    public function getInvoicePath(Order $order): ?string
    {
        $storagePath = config('invoice.storage.path', 'invoices/');
        $path = rtrim($storagePath, '/') . '/' . $order->reference . '-invoice.pdf';
        $disk = config('invoice.storage.disk', 'public');

        if (Storage::disk($disk)->exists($path)) {
            return $path;
        }

        return null;
    }

    public function downloadInvoice(Order $order)
    {
        $path = $this->getInvoicePath($order);

        if (!$path) {
            throw new \Exception('Invoice not found for order ' . $order->reference);
        }

        $disk = config('invoice.storage.disk', 'public');
        $filename = $order->reference . '-invoice.pdf';
        return Storage::disk($disk)->download($path, $filename);
    }
}