<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['phone' => 'Invalid credentials.'])->onlyInput('phone');
        }

        if (Auth::user()->role !== UserRole::Admin) {
            Auth::logout();
            return back()->withErrors(['phone' => 'You are not authorized to access the admin panel.']);
        }

        $request->session()->regenerate();

        return redirect()->intended('/admin');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
