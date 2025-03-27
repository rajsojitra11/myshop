<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'old_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6',
        ]);

        $dataChanged = false;

        // Check if Name or Email has changed
        if ($user->name !== $request->name || $user->email !== $request->email) {
            $user->name = $request->name;
            $user->email = $request->email;
            $dataChanged = true;
        }

        // Password Update
        if ($request->filled('old_password') && $request->filled('new_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->with('error', 'Old password is incorrect!');
            }

            $user->password = Hash::make($request->new_password);
            $dataChanged = true;
        }

        // Only save if there were changes
        if ($dataChanged) {
            $user->save();
            return redirect()->back()->with('success', 'Profile updated successfully!');
        } else {
            return redirect()->back()->with('error', 'No changes were made.');
        }
    }
}
