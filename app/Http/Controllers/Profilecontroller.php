<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Get logged-in user

        if (!$user) {
            return back()->withErrors(['error' => 'User not found']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'old_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6',
        ]);

        // Update Name & Email
        $user->name = $request->name;
        $user->email = $request->email;

        // Password Update
        if ($request->old_password && $request->new_password) {
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
            } else {
                return back()->withErrors(['old_password' => 'Old password is incorrect']);
            }
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
