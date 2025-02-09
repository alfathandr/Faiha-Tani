<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logout()
    {
        Auth::logout(); // Logout user

        session()->invalidate(); // Hapus session
        session()->regenerateToken(); // Regenerasi token CSRF

        return redirect('/login')->with('success', 'Anda telah logout.');
    }
}
