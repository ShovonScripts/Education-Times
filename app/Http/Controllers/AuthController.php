<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegister(): View
    {
        $districts = District::orderBy('name_bn')->get();
        return view('auth.register', compact('districts'));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'district_id' => 'required|exists:districts,id',
            'upazila' => 'required|string|max:255',
            'school_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'district_id' => $validated['district_id'],
            'upazila' => $validated['upazila'],
            'school_name' => $validated['school_name'],
            'designation' => $validated['designation'],
        ]);

        event(new Registered($user));

        return redirect()->route('verification.notice')
            ->with('success', 'নিবন্ধন সফল! আপনার ইমেইলে ভেরিফিকেশন লিংক পাঠানো হয়েছে।');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            if ($user->is_active === false) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'আপনার অ্যাকাউন্ট নিষ্ক্রিয় করা হয়েছে।',
                ])->onlyInput('email');
            }
            $request->session()->regenerate();

            if ($user->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'ইমেইল বা পাসওয়ার্ড ভুল।',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
