@extends('layouts.guest')
@section('title', 'লগইন — ' . config('app.name'))
@section('content')
<div class="min-h-screen bg-[#f5f5f5] flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">
        <a href="/" class="block text-center mb-6">
            <span class="font-serif font-black text-3xl text-[#111]">Education Times</span>
            <span class="block text-[10px] tracking-[0.3em] text-[#666] uppercase mt-1">এডুকেশন টাইমস</span>
        </a>

        <div class="bg-white border border-[#e5e5e5] p-8">
            <h1 class="font-serif font-black text-2xl text-[#111] mb-1">লগইন</h1>
            <p class="text-sm text-[#666] mb-6">আপনার অ্যাকাউন্টে প্রবেশ করুন</p>

            @if(session('status'))
            <div class="mb-4 border-l-4 border-[#111] bg-[#f5f5f5] px-4 py-3 text-sm">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">ইমেইল</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                    @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#111] mb-1.5">পাসওয়ার্ড</label>
                    <input type="password" name="password" required class="w-full border border-[#e5e5e5] focus:border-[#111] focus:outline-none focus:ring-1 focus:ring-[#111] px-3 py-2.5 text-sm">
                    @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-[#444]">
                        <input type="checkbox" name="remember" class="border-[#111] text-[#111] focus:ring-[#111]">
                        মনে রাখুন
                    </label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-[#111] hover:underline">পাসওয়ার্ড ভুলে গেছেন?</a>
                    @endif
                </div>
                <button type="submit" class="w-full bg-[#111] hover:bg-black text-white text-sm font-semibold uppercase tracking-wider py-3 transition">লগইন</button>
            </form>

            @if(Route::has('register'))
            <p class="text-sm text-center text-[#666] mt-5 pt-5 border-t border-[#e5e5e5]">
                অ্যাকাউন্ট নেই? <a href="{{ route('register') }}" class="text-[#111] font-semibold hover:underline">রেজিস্টার করুন</a>
            </p>
            @endif
        </div>
    </div>
</div>
@endsection
