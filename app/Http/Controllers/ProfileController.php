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
        $user = User::find(Auth::id());

        if (!$user) {
            return back()->with('error', 'User not found!');
        }

        // Validation
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'old_password'  => 'nullable|string',
            'new_password'  => 'nullable|string|min:6',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:5048',
        ]);

        $dataChanged = false;

        if ($user->name !== $request->name || $user->email !== $request->email) {
            $user->name  = $request->name;
            $user->email = $request->email;
            $dataChanged = true;
        }

        if ($request->filled('old_password') || $request->filled('new_password')) {

            if (!$request->filled('old_password')) {
                return back()->withErrors([
                    'old_password' => 'Old password is required.'
                ])->withInput();
            }

            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors([
                    'old_password' => 'Old password is incorrect.'
                ])->withInput();
            }

            if (!$request->filled('new_password')) {
                return back()->withErrors([
                    'new_password' => 'New password is required.'
                ])->withInput();
            }

            $user->password = Hash::make($request->new_password);
            $dataChanged = true;
        }


        if ($request->hasFile('profile_image')) {

            $file = $request->file('profile_image');
            $imageName = time() . '_' . $file->getClientOriginalName();

            $destinationPath = public_path('storage/profile_images');

            // Create folder if not exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Delete old image if exists
            if (
                $user->profile_image &&
                file_exists($destinationPath . '/' . $user->profile_image)
            ) {

                unlink($destinationPath . '/' . $user->profile_image);
            }

            // Move new image
            $file->move($destinationPath, $imageName);

            $user->profile_image = $imageName;
            $dataChanged = true;
        }


        if ($dataChanged) {
            $user->save();
            return back()->with('success', 'Profile updated successfully!');
        }

        return back()->with('error', 'No changes were made.');
    }
}
