<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
        $this->middleware('auth');
    }

    public function index()
    {
        $invoices = Invoice::with('order')
            ->where('owner_id', auth()->user()->owner_id ?? auth()->id())
            ->orderBy('generated_at', 'desc')
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        return view('invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        try {
            $invoice->markAsDownloaded();
            return $this->invoiceService->downloadInvoice($invoice->order);
        } catch (\Exception $e) {
            return back()->with('error', 'Invoice file not found or could not be downloaded.');
        }
    }

    public function generateTest(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $orderId = $request->get('order_id');
        $result = generate_test_invoice($orderId);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'invoice_path' => $result['invoice_path']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 400);
    }
}