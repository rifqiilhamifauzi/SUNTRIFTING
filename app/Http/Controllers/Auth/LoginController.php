<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showCustomerLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.customer_login');
    }

    public function logout(Request $request)
    {
        $isAdmin = Auth::check() && Auth::user()->isAdmin();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($isAdmin) {
            return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
        }

        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Quick dev/demo login for testing environment.
     */
    public function demoLogin(Request $request)
    {
        $email = $request->input('email', 'customer@suntrifting.com');
        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang kembali, Admin ' . $user->name . '!');
            }

            return redirect()->intended(route('home'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return redirect()->route('login')->with('error', 'Akun demo tidak ditemukan.');
    }
}

