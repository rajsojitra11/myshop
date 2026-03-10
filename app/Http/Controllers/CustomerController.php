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

    /**
     * Show change password form for customer
     */
    public function showChangePasswordForm()
    {
        if (!Session::has('customer_email')) {
            return redirect()->route('index');
        }

        $email = Session::get('customer_email');
        $mobile = Session::get('customer_mobile');

        // Get the first invoice record for this customer to edit password
        $invoice = Invoice::where('to_email', $email)
            ->orWhere('mobile_no', $mobile)
            ->first();

        return view('customer.change-password', compact('invoice'));
    }

    /**
     * Handle customer password change
     */
    public function updatePassword(Request $request)
    {
        if (!Session::has('customer_email')) {
            return redirect()->route('index');
        }

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Current password is required',
            'new_password.required' => 'New password is required',
            'new_password.min' => 'Password must be at least 6 characters',
            'new_password.confirmed' => 'Passwords do not match',
        ]);

        $email = Session::get('customer_email');
        $mobile = Session::get('customer_mobile');

        // Get the first invoice for this customer
        $invoice = Invoice::where('to_email', $email)
            ->orWhere('mobile_no', $mobile)
            ->first();

        if (!$invoice) {
            return back()->withErrors(['email' => 'Invoice not found'])->withInput();
        }

        // Verify current password - use mobile_no as default if no password set
        $currentPassword = $invoice->password ? $invoice->password : $invoice->mobile_no;
        
        if ($request->current_password !== $currentPassword) {
            return back()->withErrors(['current_password' => 'Current password is incorrect'])->withInput();
        }

        // Update password for all invoices of this customer
        Invoice::where('to_email', $email)
            ->orWhere('mobile_no', $mobile)
            ->update(['password' => $request->new_password]);

        return redirect()->route('customer.dashboard')->with('success', 'Password changed successfully!');
    }}