<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        if (!config('services.google.client_id') || !config('services.google.client_secret')) {
            return redirect()->route('login')->with(
                'error',
                'Konfigurasi Google Client ID belum diatur di .env. Gunakan tombol "Login Demo Customer" untuk pengujian.'
            );
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find by google_id or email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update google_id and avatar if needed
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar() ?: $user->avatar,
                ]);
            } else {
                // Auto register new customer
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]);

                // Create initial cart
                Cart::firstOrCreate(['user_id' => $user->id]);
            }

            Auth::login($user, true);

            return redirect()->intended(route('home'))
                ->with('success', 'Selamat datang, ' . $user->name . '!');
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login via Google: ' . $e->getMessage());
        }
    }
}

