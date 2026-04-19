<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Supplier;
use App\Models\PasswordResetOtp;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // ═══════════════════════════════════════════════════════════════════════════
    //  ADMIN FORGOT PASSWORD
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Show the form to enter email address
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP to the user's email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        $this->generateAndSendOtp($request->email);

        return redirect()->route('password.verify-otp-form')
                         ->with('email', $request->email)
                         ->with('success', 'OTP has been sent to your email!');
    }

    /**
     * Show the OTP verification form
     */
    public function showVerifyOtpForm(Request $request)
    {
        $email = session('email') ?? $request->email;

        if (!$email) {
            return redirect()->route('password.forgot')->withErrors(['email' => 'Please enter your email first.']);
        }

        return view('auth.verify-otp', compact('email'));
    }

    /**
     * Verify the OTP entered by user
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $otpRecord = $this->validateOtp($request->email, $request->otp);

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.'])
                         ->withInput();
        }

        // Mark OTP as verified
        $otpRecord->update(['verified' => true]);

        return redirect()->route('password.reset-form')
                         ->with('email', $request->email)
                         ->with('otp_verified', true);
    }

    /**
     * Show the reset password form
     */
    public function showResetForm(Request $request)
    {
        $email = session('email');
        $otpVerified = session('otp_verified');

        if (!$email || !$otpVerified) {
            return redirect()->route('password.forgot')->withErrors(['email' => 'Please verify your OTP first.']);
        }

        return view('auth.reset-password', compact('email'));
    }

    /**
     * Reset the password in database
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Double-check that OTP was verified for this email
        $otpRecord = PasswordResetOtp::where('email', $request->email)
                                      ->where('verified', true)
                                      ->first();

        if (!$otpRecord) {
            return redirect()->route('password.forgot')
                             ->withErrors(['email' => 'OTP verification required. Please start again.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Clean up OTP records
        PasswordResetOtp::where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password reset successfully! Please login with your new password.');
    }

    /**
     * Resend OTP to user's email (admin)
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $this->generateAndSendOtp($request->email);

        return back()->with('email', $request->email)
                     ->with('success', 'A new OTP has been sent to your email!');
    }

    // ═══════════════════════════════════════════════════════════════════════════
    //  CUSTOMER FORGOT PASSWORD
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Show customer forgot password form
     */
    public function showCustomerForgotForm()
    {
        return view('auth.customer-forgot-password');
    }

    /**
     * Send OTP to customer's email (lookup from invoices table)
     */
    public function customerSendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Find customer in invoices
        $invoice = Invoice::where('to_email', $request->email)->first();

        if (!$invoice) {
            return back()->withErrors(['email' => 'No invoice found with this email address.'])->withInput();
        }

        $this->generateAndSendOtp($request->email);

        return redirect()->route('customer.password.verify-otp-form')
                         ->with('email', $request->email)
                         ->with('success', 'OTP has been sent to your email!');
    }

    /**
     * Show OTP verification form for customer
     */
    public function showCustomerVerifyOtpForm(Request $request)
    {
        $email = session('email') ?? $request->email;

        if (!$email) {
            return redirect()->route('customer.password.forgot')
                             ->withErrors(['email' => 'Please enter your email first.']);
        }

        $type = 'customer';
        return view('auth.verify-otp-credentials', compact('email', 'type'));
    }

    /**
     * Verify OTP and redirect to reset password form
     */
    public function customerVerifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $otpRecord = $this->validateOtp($request->email, $request->otp);

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.'])
                         ->withInput();
        }

        // Mark OTP as verified
        $otpRecord->update(['verified' => true]);

        return redirect()->route('customer.password.reset-form')
                         ->with('email', $request->email)
                         ->with('otp_verified', true);
    }

    /**
     * Show customer reset password form
     */
    public function showCustomerResetForm()
    {
        $email = session('email');
        $otpVerified = session('otp_verified');

        if (!$email || !$otpVerified) {
            return redirect()->route('customer.password.forgot')
                             ->withErrors(['email' => 'Please verify your OTP first.']);
        }

        $type = 'customer';
        return view('auth.reset-password-role', compact('email', 'type'));
    }

    /**
     * Reset customer password
     */
    public function customerResetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Verify OTP was verified
        $otpRecord = PasswordResetOtp::where('email', $request->email)
                                      ->where('verified', true)
                                      ->first();

        if (!$otpRecord) {
            return redirect()->route('customer.password.forgot')
                             ->withErrors(['email' => 'OTP verification required. Please start again.']);
        }

        // Create or update customer password
        \App\Models\CustomerPassword::updateOrCreate(
            ['email' => $request->email],
            ['password' => Hash::make($request->password)]
        );

        // Clean up OTP records
        PasswordResetOtp::where('email', $request->email)->delete();

        return redirect()->route('login')
                         ->with('success', 'Password reset successfully! Please login with your new password.');
    }

    /**
     * Resend OTP for customer
     */
    public function customerResendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $invoice = Invoice::where('to_email', $request->email)->first();
        if (!$invoice) {
            return back()->withErrors(['email' => 'No invoice found with this email.']);
        }

        $this->generateAndSendOtp($request->email);

        return back()->with('email', $request->email)
                     ->with('success', 'A new OTP has been sent to your email!');
    }

    // ═══════════════════════════════════════════════════════════════════════════
    //  SUPPLIER FORGOT PASSWORD
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Show supplier forgot password form
     */
    public function showSupplierForgotForm()
    {
        return view('auth.supplier-forgot-password');
    }

    /**
     * Send OTP to supplier's email
     */
    public function supplierSendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $supplier = Supplier::where('email', $request->email)->first();

        if (!$supplier) {
            return back()->withErrors(['email' => 'No supplier found with this email address.'])->withInput();
        }

        $this->generateAndSendOtp($request->email);

        return redirect()->route('supplier.password.verify-otp-form')
                         ->with('email', $request->email)
                         ->with('success', 'OTP has been sent to your email!');
    }

    /**
     * Show OTP verification form for supplier
     */
    public function showSupplierVerifyOtpForm(Request $request)
    {
        $email = session('email') ?? $request->email;

        if (!$email) {
            return redirect()->route('supplier.password.forgot')
                             ->withErrors(['email' => 'Please enter your email first.']);
        }

        $type = 'supplier';
        return view('auth.verify-otp-credentials', compact('email', 'type'));
    }

    /**
     * Verify OTP and redirect to reset password form
     */
    public function supplierVerifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $otpRecord = $this->validateOtp($request->email, $request->otp);

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.'])
                         ->withInput();
        }

        // Mark OTP as verified
        $otpRecord->update(['verified' => true]);

        return redirect()->route('supplier.password.reset-form')
                         ->with('email', $request->email)
                         ->with('otp_verified', true);
    }

    /**
     * Show supplier reset password form
     */
    public function showSupplierResetForm()
    {
        $email = session('email');
        $otpVerified = session('otp_verified');

        if (!$email || !$otpVerified) {
            return redirect()->route('supplier.password.forgot')
                             ->withErrors(['email' => 'Please verify your OTP first.']);
        }

        $type = 'supplier';
        return view('auth.reset-password-role', compact('email', 'type'));
    }

    /**
     * Reset supplier password
     */
    public function supplierResetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Verify OTP was verified
        $otpRecord = PasswordResetOtp::where('email', $request->email)
                                      ->where('verified', true)
                                      ->first();

        if (!$otpRecord) {
            return redirect()->route('supplier.password.forgot')
                             ->withErrors(['email' => 'OTP verification required. Please start again.']);
        }

        // Update supplier password
        $supplier = Supplier::where('email', $request->email)->first();
        $supplier->password = Hash::make($request->password);
        $supplier->save();

        // Clean up OTP records
        PasswordResetOtp::where('email', $request->email)->delete();

        return redirect()->route('login')
                         ->with('success', 'Password reset successfully! Please login with your new password.');
    }

    /**
     * Resend OTP for supplier
     */
    public function supplierResendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $supplier = Supplier::where('email', $request->email)->first();
        if (!$supplier) {
            return back()->withErrors(['email' => 'No supplier found with this email.']);
        }

        $this->generateAndSendOtp($request->email);

        return back()->with('email', $request->email)
                     ->with('success', 'A new OTP has been sent to your email!');
    }

    // ═══════════════════════════════════════════════════════════════════════════
    //  SHARED HELPERS
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Generate a 6-digit OTP, save it, and send via email
     */
    private function generateAndSendOtp(string $email): void
    {
        // Delete any previous OTPs for this email
        PasswordResetOtp::where('email', $email)->delete();

        // Generate a 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save OTP with 10-minute expiry
        PasswordResetOtp::create([
            'email'      => $email,
            'otp'        => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP via email
        Mail::raw(
            "Your Myshop verification OTP is: {$otp}\n\nThis OTP is valid for 10 minutes.\n\nIf you did not request this, please ignore this email.",
            function ($message) use ($email) {
                $message->to($email)->subject('Myshop - Verification OTP');
            }
        );
    }

    /**
     * Validate an OTP for a given email
     */
    private function validateOtp(string $email, string $otp): ?PasswordResetOtp
    {
        return PasswordResetOtp::where('email', $email)
                                ->where('otp', $otp)
                                ->where('expires_at', '>', Carbon::now())
                                ->first();
    }
}

