<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate Form Data
        $request->validate([
            'firstname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Insert User into Database
        $user = User::create([
            'name' => $request->firstname, // Laravel's default users table has "name" instead of "firstname"
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log the user in after registration
        Auth::login($user);


        // Redirect to Dashboard or Home
        return redirect()->route('login')->with('success', 'Registration Successful');
    }
}
