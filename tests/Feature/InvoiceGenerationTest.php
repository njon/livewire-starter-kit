<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class InvoiceGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create storage directory for testing
        Storage::fake('public');
    }

    public function test_generate_test_invoice_function_works()
    {
        // Create a test user and order
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'payment-received',
            'reference' => 'TEST-REF-' . uniqid(),
            'customer_reference' => 'CUST-' . uniqid(),
            'placed_at' => now(),
            'sub_total' => 1000,
            'total' => 1200,
            'currency_code' => 'EUR',
            'channel_id' => 1,
            'discount_total' => 0,
            'tax_total' => 200,
            'shipping_total' => 0,
            'tax_breakdown' => [],
        ]);

        // Test the helper function
        $result = generate_test_invoice($order->id);

        $this->assertTrue($result['success']);
        $this->assertEquals($order->id, $result['order_id']);
        $this->assertStringContains('Test invoice generated successfully', $result['message']);
        $this->assertNotNull($result['invoice_path']);

        // Check that invoice record was created
        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'owner_id' => $user->id
        ]);
    }

    public function test_invoice_service_generates_invoice()
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'payment-received',
            'reference' => 'TEST-REF-' . uniqid(),
            'customer_reference' => 'CUST-' . uniqid(),
            'placed_at' => now(),
            'sub_total' => 1500,
            'total' => 1800,
            'currency_code' => 'EUR',
            'channel_id' => 1,
            'discount_total' => 0,
            'tax_total' => 300,
            'shipping_total' => 0,
            'tax_breakdown' => [],
        ]);

        $invoiceService = new InvoiceService();
        $invoicePath = $invoiceService->generateInvoice($order);

        $this->assertNotNull($invoicePath);
        $this->assertStringContains($order->reference, $invoicePath);
        $this->assertTrue(Storage::disk('public')->exists($invoicePath));
    }

    public function test_invoice_generation_handles_missing_billing_address()
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'payment-received',
            'reference' => 'NO-ADDRESS-' . uniqid(),
            'customer_reference' => 'CUST-' . uniqid(),
            'placed_at' => now(),
            'sub_total' => 1000,
            'total' => 1200,
            'currency_code' => 'EUR',
            'channel_id' => 1,
            'discount_total' => 0,
            'tax_total' => 200,
            'shipping_total' => 0,
            'tax_breakdown' => [],
        ]);

        $invoiceService = new InvoiceService();

        // This should not throw an exception even without billing address
        $invoicePath = $invoiceService->generateInvoice($order);

        $this->assertNotNull($invoicePath);
        $this->assertTrue(Storage::disk('public')->exists($invoicePath));
    }
}