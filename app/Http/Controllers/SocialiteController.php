<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('google_id', $googleUser->id)->first();

        if ($user) {
            if (!$user->is_active) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['email' => 'আপনার অ্যাকাউন্ট নিষ্ক্রিয় করা হয়েছে।']);
            }
            Auth::login($user);
            return $this->redirectToDashboard($user);
        }

        $existingUser = User::where('email', $googleUser->email)->first();

        if ($existingUser) {
            if (!$existingUser->is_active) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['email' => 'আপনার অ্যাকাউন্ট নিষ্ক্রিয় করা হয়েছে।']);
            }
            $existingUser->update([
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
            ]);
            Auth::login($existingUser);
            return $this->redirectToDashboard($existingUser);
        }

        $user = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'avatar' => $googleUser->avatar,
            'password' => bcrypt(Str::random(16)),
            'email_verified_at' => now(),
        ]);

        Auth::login($user);
        return $this->redirectToDashboard($user);
    }

    private function redirectToDashboard(User $user): RedirectResponse
    {
        if ($user->is_admin) {
            return redirect()->intended(route('admin.dashboard'));
        }
        return redirect()->intended(route('dashboard'));
    }
}
