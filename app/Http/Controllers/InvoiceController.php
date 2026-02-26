<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
    /**
     * Show list of customers (invoices summary)
     */
    public function showCustomers()
    {
        $invoices = Invoice::with('products')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.customer', compact('invoices'));
    }

    /**
     * Show full invoice with products
     */
    public function show($id)
    {
        $invoice = Invoice::with('products')->findOrFail($id);

        return view('admin.invoice_show', compact('invoice'));
    }

    /**
     * Show invoice creation form
     */
    public function create()
    {
        $user = Auth::user();
        $products = Product::where('user_id', $user->id)->get();

        return view('admin.invoice', compact('products'));
    }

    /**
     * Store a new invoice with its products
     */
    public function store(Request $request)
    {
        $request->validate([
            'to_name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'bill_no' => 'required|string|max:50',
            'invoice_number' => 'required|string|max:50',
            'paymentMethod' => 'required|string|max:50',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string',
            'products.*.qty' => 'required|numeric',
            'products.*.rate' => 'required|numeric',
            'products.*.amount' => 'required|numeric',
        ]);

        DB::transaction(function () use ($request) {

            // Save Invoice
            $invoice = Invoice::create([
                'user_id' => Auth::id(),
                'to_name' => $request->to_name,
                'to_address' => $request->to_address,
                'to_email' => $request->to_email,
                'mobile_no' => $request->mobile_no,
                'bill_no' => $request->bill_no,
                'invoice_number' => $request->invoice_number,
                'order_id' => $request->order_id,
                'account_number' => $request->account_number,
                'payment_method' => $request->paymentMethod,
                'subtotal' => (float) $request->subtotal,
                'tax' => (float) $request->tax,
                'shipping' => (float) ($request->shipping ?? 0),
                'total' => (float) $request->total,
            ]);

            // Save Invoice Products
            foreach ($request->products as $product) {
                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'serial' => $product['serial'] ?? '',
                    'name' => $product['name'],
                    'description' => $product['description'] ?? '',
                    'qty' => (float) $product['qty'],
                    'rate' => (float) $product['rate'],
                    'amount' => (float) $product['amount'],
                ]);
            }

            Log::info("Invoice {$invoice->id} created by user " . Auth::id());
        });

        return redirect()->route('invoice.create')->with('success', 'Invoice saved successfully.');
    }

    /**
     * Generate PDF for invoice
     */
    public function generatePdf($id)
    {
        $invoice = Invoice::with('products')->findOrFail($id);
        
        $pdf = Pdf::loadView('invoice-pdf', compact('invoice'));
        
        return $pdf->download('invoice-' . $invoice->bill_no . '.pdf');
    }

    /**
     * Send invoice PDF via WhatsApp
     */
    public function sendWhatsApp(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
        ]);

        $invoice = Invoice::with('products')->findOrFail($request->invoice_id);

        try {
            // Clean mobile number
            $mobile = preg_replace('/\D/', '', $invoice->mobile_no);
            
            // Add country code if not present (assuming India +91)
            if (!str_starts_with($mobile, '91') && strlen($mobile) === 10) {
                $mobile = '91' . $mobile;
            }

            // Generate PDF
            $pdf = Pdf::loadView('invoice-pdf', compact('invoice'));
            $pdfContent = $pdf->output();
            $pdfFileName = 'invoice-' . $invoice->bill_no . '.pdf';

            // Create temporary file
            $tempPath = storage_path('temp/' . $pdfFileName);
            if (!file_exists(storage_path('temp'))) {
                mkdir(storage_path('temp'), 0755, true);
            }
            file_put_contents($tempPath, $pdfContent);

            // For demonstration: Create a message with WhatsApp Web link
            // In production, you would use WhatsApp Business API
            $message = "Hi " . $invoice->to_name . ",\n\n";
            $message .= "Here is your invoice:\n";
            $message .= "Bill No: " . $invoice->bill_no . "\n";
            $message .= "Total: ₹" . number_format($invoice->total, 2) . "\n\n";
            $message .= "The invoice PDF has been attached.";

            // Encode and create WhatsApp URL with message
            // Note: WhatsApp Web doesn't support direct file sending via URL
            // For production, use WhatsApp Business API with a proper integration
            $encodedMessage = urlencode($message);
            $whatsappUrl = "https://wa.me/" . $mobile . "?text=" . $encodedMessage;

            // Return success response with WhatsApp URL and PDF download link
            return response()->json([
                'success' => true,
                'message' => 'Invoice PDF generated successfully',
                'whatsapp_url' => $whatsappUrl,
                'pdf_url' => route('invoice.pdf', $invoice->id),
                'mobile' => $mobile
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp send error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
