<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show admin profile page
     */
    public function showProfile()
    {
        $admin = Auth::user();
        return view('admin.profile', compact('admin'));
    }

    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'no_telepon' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('admin.profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Show change password page
     */
    public function showChangePassword()
    {
        return view('admin.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $admin = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $admin->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini tidak sesuai!'])->withInput();
        }

        // Update password
        $admin->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('admin.profile.show')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Show preferences page
     */
    public function showPreferences()
    {
        return view('admin.preferences');
    }

    /**
     * Update preferences
     */
    public function updatePreferences(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'timezone' => 'required|string',
            'theme' => 'required|in:light,dark',
            'notifications' => 'required|in:on,off',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $admin = Auth::user();
        
        // Store preferences in session or database
        session()->put('admin_preferences', [
            'timezone' => $request->timezone,
            'theme' => $request->theme,
            'notifications' => $request->notifications,
        ]);

        return redirect()->route('admin.preferences.show')->with('success', 'Preferensi berhasil disimpan!');
    }
}
