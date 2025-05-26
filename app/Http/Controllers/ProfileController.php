<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();

        return view('pages.profile', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email,' . $user->id,
                'current_password' => 'required|string',
                'password' => 'nullable|string|min:5',
            ]);

            // Verifikasi password saat ini
            if (!Hash::check($validatedData['current_password'], $user->password)) {
                return back()->with('error', 'Password saat ini salah.');
            }

            // Update nama dan email
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];

            // Update password jika ada input baru
            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            $user->save();

            return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Gagal memperbarui profil: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
