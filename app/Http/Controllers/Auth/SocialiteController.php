<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect pengguna ke halaman otentikasi Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Objek callback dari Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Cari user berdasarkan email atau google_id
            $user = User::where('email', $googleUser->getEmail())
                ->orWhere('google_id', $googleUser->getId())
                ->first();

            if ($user) {
                // Jika user sudah ada, perbarui google_id dan avatar jika belum terisi
                $user->update([
                    'google_id' => $user->google_id ?? $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Jika user belum ada, buat akun baru otomatis
                $user = User::create([
                    'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Pengguna Google',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'user',
                ]);
            }

            // Login-kan user ke sistem Laravel
            Auth::login($user, true);

            // Jika role admin dan tidak ada rute intended (misal checkout), arahkan ke admin dashboard
            if ($user->role === 'admin' && !session()->has('url.intended')) {
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil masuk dengan akun Google!');
            }

            // Redirect ke rute intended (misal rute checkout yang sempat diakses sebelumnya)
            return redirect()->intended(route('home'))->with('success', 'Berhasil masuk dengan akun Google!');

        } catch (\Exception $e) {
            \Log::error('Gagal Login Google Socialite: ' . $e->getMessage());

            return redirect()->route('login')->with('error', 'Gagal melakukan login dengan Google: ' . $e->getMessage());
        }
    }
}
