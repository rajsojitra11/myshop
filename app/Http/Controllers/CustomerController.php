<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    /**
     * Redirect to main login page
     */
    public function showLoginForm()
    {
        if (Session::has('customer_email')) {
            return redirect()->route('customer.dashboard');
        }
        return redirect()->route('index');
    }

    /**
     * Handle customer login - verify email/mobile in invoices table
     */
    public function login(Request $request)
    {
        $request->validate([
            'email_mobile' => 'required|string',
            'password' => 'required|string|min:1',
        ], [
            'email_mobile.required' => 'Email or Mobile is required',
            'password.required' => 'Password is required',
        ]);

        // Find invoices matching email or mobile
        $invoice = Invoice::where('to_email', $request->email_mobile)
            ->orWhere('mobile_no', $request->email_mobile)
            ->first();

        if (!$invoice) {
            return back()->withErrors(['email_mobile' => 'No invoice found with this email or mobile'])->withInput();
        }

        // Simple password verification - you can use the mobile_no as password
        // Or implement your own logic here
        if ($request->password !== $invoice->mobile_no) {
            return back()->withErrors(['password' => 'Invalid password'])->withInput();
        }

        // Store customer session
        Session::put('customer_email', $invoice->to_email);
        Session::put('customer_mobile', $invoice->mobile_no);
        Session::put('customer_name', $invoice->to_name);

        return redirect()->route('customer.dashboard')->with('success', 'Login successful!');
    }

    /**
     * Show customer dashboard with all their invoices
     */
    public function dashboard()
    {
        if (!Session::has('customer_email')) {
            return redirect()->route('index');
        }

        $email = Session::get('customer_email');
        $mobile = Session::get('customer_mobile');

        // Get all invoices for this customer
        $invoices = Invoice::with('products')
            ->where('to_email', $email)
            ->orWhere('mobile_no', $mobile)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.dashboard', compact('invoices'));
    }

    /**
     * Show specific invoice for customer
     */
    public function showInvoice($id)
    {
        if (!Session::has('customer_email')) {
            return redirect()->route('index');
        }

        $email = Session::get('customer_email');
        $mobile = Session::get('customer_mobile');
        
        $invoice = Invoice::with('products')->findOrFail($id);

        // Verify that invoice belongs to this customer
        if ($invoice->to_email !== $email && $invoice->mobile_no !== $mobile) {
            abort(403, 'Unauthorized access to this invoice');
        }

        return view('customer.invoice_show', compact('invoice'));
    }

    /**
     * Logout customer
     */
    public function logout()
    {
        Session::forget(['customer_email', 'customer_mobile', 'customer_name']);
        return redirect()->route('index')->with('success', 'Logged out successfully!');
    }
}
